<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('requirement_m');
		$this->load->model('attribute_m');
	}

	public function index($id = NULL)
	{
		if(!$id) {
			echo 'lol';
		}
		else {
			$this->data['requirement'] = $this->requirement_m->get($id);
			$this->data['requirements'] = $this->requirement_m->get_by('section_id = '. $this->data['requirement']->section_id);
			$this->data['attributes'] = $this->attribute_m->get_by('req_id = '. $id);

			$this->load->view('requirement', $this->data);
		}
	}

	public function delete($id)
	{
		if($id) {
			$req_id = $this->attribute_m->get($id);
			$this->attribute_m->delete($id);
		}
		redirect('requirement/'. $req_id->req_id);
	}
}
