<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('requirement_m');
		$this->load->model('attribute_m');
	}

	public function delete($id)
	{
		$attr = $this->attribute_m->get($id);

		// получаем все требования из секции-родителя
		$requirements = $this->requirement_m->get_requirements_from_section_by_id($attr->req_id);

		// пробегаемся по каждому требованию и удаляем атрибут с определенным тайтлом
		foreach ($requirements as $r) {
			$this->db->where('title', $attr->title);
			$attributes = $this->attribute_m->get_by('req_id = '. $r->id);
			foreach ($attributes as $a) {
				$this->attribute_m->delete($a->id);
			}
		}

		redirect('requirement/'. $attr->req_id);
	}
}
