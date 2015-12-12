<?php
class Changes_m extends MY_Model
{
	protected $_primary_key = 'id';
	protected $_table_name = 'changes';
	protected $_order_by = 'id asc, order desc';

	public $rules = array(
		'title' => array(
			'field' => 'title', 
			'label' => 'Title', 
			'rules' => 'trim|required|max_length[128]'
		),
		'body' => array(
			'field' => 'body', 
			'label' => 'Body', 
			'rules' => 'trim|required|max_length[128]'
		)
	);

	public function get_new()
	{
		$model = new stdClass();
		$model->title = '';
		$model->body = 0;
		return $model;
	}
	
	public function delete($id)
	{
		// Delete an atribute
		parent::delete($id);
	}
}