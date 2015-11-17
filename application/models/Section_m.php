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

	public function get_redacted($project_id)
	{
		$this->db->select()->from('sections')->where('project_id', $project_id)->order_by($this->_order_by);
		$result = $this->db->get()->result_array();

        $sections = array();
        foreach($result as $r) {
            $r['children'] = array();
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

	public function save_order($pages)
	{
		if (count($pages)) {
			foreach ($pages as $order => $page) {
				if ($page['item_id'] != '') {
					$data = array('parent_id' => (int) $page['parent_id']);
					$this->db->set($data)->where($this->_primary_key, $page['item_id'])->update($this->_table_name);
				}
			}
		}
	}

	public function save_order2($pages)
	{
		// $pages = json_decode($pages);
		if (count($pages)) {
			$i = 1;
			// dump($pages);
			foreach ($pages as $s) {
				if ($s['item_id'] != '') {
					$data = array('parent_id' => (int) $s['parent_id']);
					$this->db->set($data)->where($this->_primary_key, $s['item_id'])->update($this->_table_name);
				}
			}
		}
	}

	public function orderr($sections, $parent_id = 0, $order = 1)
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
					$this->orderr($s['children'], $s['id'], $order);
			}
		}
	}

	// public function get_nested ()
	// {
	// 	$this->db->select('id, title, s_parent_id')->order_by($this->_order_by);
	// 	$pages = $this->db->get('pages')->result_array();
		
	// 	$array = array();
	// 	foreach ($pages as $page) {
	// 		if (! $page['s_parent_id']) {
	// 			// This page has no parent
	// 			$array[$page['id']] = $page;
	// 		}
	// 		else {
	// 			// This is a child page
	// 			$array[$page['s_parent_id']]['children'][] = $page;
	// 		}
	// 	}
	// 	return $array;
	// }

	public function get_nested()
	{
		$this->db->select('id, title, s_parent_id')->order_by($this->_order_by);
		$pages = $this->db->get('attributes')->result_array();
		
		$levels = array();
        foreach($pages as $r){
            $r['children'] = array();
            $levels[$r['id']] = $r;
        }

        // создаем комментную иерархию с ссылками внутри массива
		foreach ($levels as $c => &$v) {
			if ($v['s_parent_id'] != 0) {
				$levels[$v['s_parent_id']]['children'][] =& $v;
			}
		}
		unset($v);

		// удаляем всякий мусор c первого уровня со знаком &
		foreach ($levels as $c => $v) {
			if ($v['s_parent_id'] != 0)
				unset($levels[$c]);
		}

		return $levels;
	}

	public function get_with_parent ($id = NULL, $single = FALSE)
	{
		$this->db->select('pages.*, p.slug as parent_slug, p.title as parent_title');
		$this->db->join('pages as p', 'pages.s_parent_id=p.id', 'left');
		return parent::get($id, $single);
	}

	public function get_no_parents()
	{
		// Fetch pages without parents
		$this->db->select('id, title');
		$this->db->where('s_parent_id', 0);
		$pages = parent::get();
		
		// Return key => value pair array
		$array = array(
			0 => 'No parent'
		);
		if (count($pages)) {
			foreach ($pages as $page) {
				$array[$page->id] = $page->title;
			}
		}
		
		return $array;
	}
}