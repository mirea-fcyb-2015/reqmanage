<?php
class Requirement_m extends MY_Model
{
	protected $_primary_key = 'id';
	protected $_table_name = 'requirements';
	protected $_order_by = 'order asc, id asc';

	public $rules = array(
		'title' => array(
			'field' => 'title', 
			'label' => 'Title', 
			'rules' => 'trim|max_length[255]|required'
		),
		'description' => array(
			'field' => 'description', 
			'label' => 'Description', 
			'rules' => 'trim|required'
		)
	);

	public function get_new()
	{
		$model = new stdClass();
		$model->title = '';
		$model->description = '';
		return $model;
	}
	
	public function delete($id)
	{
		$this->db->where('req_id', $id);
		$this->db->delete('attributes');
		
		// Delete an atribute
		parent::delete($id);
	}
}