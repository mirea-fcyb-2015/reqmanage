<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends CI_Controller {

	public $what = 3; // cms_config/hierarchy

	public function __construct(){
		parent::__construct();
		$this->load->model('requirement_m');
		$this->load->model('attribute_m');

		// проверка на может ли пользователь редактировать
	}

	public function delete($id)
	{
		$attr = $this->attribute_m->get($id);

		if($attr) {
			// получаем все требования из секции-родителя
			$requirements = $this->requirement_m->get_requirements_from_section_by_id($attr->req_id);

			// пробегаемся по каждому требованию и удаляем атрибут с определенным тайтлом
			foreach ($requirements as $r) {
				$this->db->where('title', $attr->title);
				$this->attribute_m->delete_by('req_id = '. $r->id);
			}

			$this->change_m->add($this->what, $attr->req_id, 'Удален атрибут ('. $attr->title .')');

			redirect('requirement/'. $attr->req_id);
		}
	}
}
