<?php
class Project_m extends MY_Model
{
	protected $_primary_key = 'id';
	protected $_table_name = 'projects';
	protected $_order_by = 'created_at asc';

	public $rules = array(
		'title' => array(
			'field' => 'title', 
			'label' => 'Title', 
			'rules' => 'trim|max_length[255]|required'
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
		parent::delete($id);
	}

	public function save_order ($pages)
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

	// public function get_nested ()
	// {
	// 	$this->db->select('id, title, parent_id')->order_by($this->_order_by);
	// 	$pages = $this->db->get('pages')->result_array();
		
	// 	$array = array();
	// 	foreach ($pages as $page) {
	// 		if (! $page['parent_id']) {
	// 			// This page has no parent
	// 			$array[$page['id']] = $page;
	// 		}
	// 		else {
	// 			// This is a child page
	// 			$array[$page['parent_id']]['children'][] = $page;
	// 		}
	// 	}
	// 	return $array;
	// }

	public function get_nested()
	{
		$this->db->select('id, title, parent_id')->order_by($this->_order_by);
		$pages = $this->db->get('attributes')->result_array();
		
		$levels = array();
        foreach($pages as $r){
            $r['children'] = array();
            $levels[$r['id']] = $r;
        }

        // создаем комментную иерархию с ссылками внутри массива
		foreach ($levels as $c => &$v) {
			if ($v['parent_id'] != 0) {
				$levels[$v['parent_id']]['children'][] =& $v;
			}
		}
		unset($v);

		// удаляем всякий мусор c первого уровня со знаком &
		foreach ($levels as $c => $v) {
			if ($v['parent_id'] != 0)
				unset($levels[$c]);
		}

		return $levels;
	}

	public function get_with_parent ($id = NULL, $single = FALSE)
	{
		$this->db->select('pages.*, p.slug as parent_slug, p.title as parent_title');
		$this->db->join('pages as p', 'pages.parent_id=p.id', 'left');
		return parent::get($id, $single);
	}

	public function get_no_parents()
	{
		// Fetch pages without parents
		$this->db->select('id, title');
		$this->db->where('parent_id', 0);
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