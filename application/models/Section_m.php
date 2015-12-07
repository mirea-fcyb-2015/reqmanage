<?php
class Section_m extends MY_Model
{
	protected $_primary_key = 'id';
	protected $_table_name = 'sections';
	protected $_order_by = 'order asc, id asc';

	public $rules = array(
		'title' => array(
			'field' => 's_title', 
			'label' => 'Title', 
			'rules' => 'trim|max_length[256]|required'
		)
	);

	public function get_new()
	{
		$model = new stdClass();
		$model->title = '';
		return $model;
	}
	
	public function delete($id)
	{
		// Delete an atribute
		parent::delete($id);
		
		// Reset parent ID for its children
		$this->db->set(array(
			's_parent_id' => 0
		))->where('s_parent_id', $id)->update($this->_table_name);
	}

	public function add_default($project_id)
	{
		$sections = array('Функциональные требования', 'Требования к надежности', 'Условия эксплуатации', 'Требования к составу и параметрам технических средств',
						  'Требования к информационной и программной совместимости', 'Требования к маркировке и упаковке', 'Требования к транспортированию и хранению',
						  'Специальные требования');

		foreach ($sections as $s) {
			$data['title'] = $s;
			$data['project_id'] = $project_id;
			$data['parent_id'] = 0;

			parent::save($data);
		}
	}

	public function count_requirements($id)
	{
		$this->db->select('id')->from('requirements')->where('section_id', $id);
		return $this->db->count_all_results();
	}

	public function get_redacted($project_id)
	{
		$this->db->select()->from('sections')->where('project_id', $project_id)->order_by($this->_order_by);
		$result = $this->db->get()->result_array();

        $sections = array();
        foreach($result as $r) {
            $r['children'] = array();
            $r['requirements'] = $this->count_requirements($r['id']);
            $sections[$r['id']] = $r;
        }

        // создаем комментную иерархию с ссылками внутри массива
		foreach ($sections as $c => &$v) {
			if ($v['parent_id'] != 0) {
				$sections[$v['parent_id']]['children'][] =& $v;
			}
		}
		unset($v);

		// удаляем всякий мусор c первого уровня со знаком &
		foreach ($sections as $c => $v) {
			if ($v['parent_id'] != 0)
				unset($sections[$c]);
		}

		return $sections;
	}

	public function get_project_title($pr_id)
	{
		$project = $this->db->select('title')->from('projects')->where('id', $pr_id)->get()->row();
		return $project->title;
	}

	public function order($sections, $parent_id = 0, $order = 1)
	{
		if (count($sections)) {
			// dump($pages);
			foreach ($sections as $s) {
				$data = array(
					'parent_id' => $parent_id,
					'order' => $order
					);
				$this->db->set($data)->where($this->_primary_key, $s['id'])->update($this->_table_name);
				$order++;

				if(!empty($s['children']))
					$this->order($s['children'], $s['id'], $order);
			}
		}
	}
}