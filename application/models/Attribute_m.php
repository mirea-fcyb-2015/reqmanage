<?php
class Attribute_m extends MY_Model
{
	protected $_primary_key = 'id';
	protected $_table_name = 'attributes';
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

	public function add_default($req_id)
	{
		$attr = array('Статус', 'Приоритет', 'Трудоёмкость', 'Стабильность',
						  'Целевая версия', 'Назначение');

		foreach ($attr as $a) {
			$data['title'] = $a;
			$data['req_id'] = $req_id;
			$data['body'] = 'body';

			parent::save($data);
		}
	}
}