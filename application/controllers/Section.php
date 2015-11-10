<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('section_m');
		$this->load->model('requirement_m');
		$this->load->model('attribute_m');
	}

	public function index($id = NULL)
	{
		if(!$id) {
			echo 'lol';
		}
		else {
			if($this->input->post('req_title')) {
				$data['section_id'] = $id;
				$data['title'] = $this->input->post('req_title');

				$req_id = $this->requirement_m->save($data);
				$this->attribute_m->add_default($req_id);
			}

			$this->data['section'] = $this->section_m->get($id);
			$this->data['sections'] = $this->section_m->get_redacted($this->data['section']->project_id);
			$this->data['requirements'] = $this->requirement_m->get_by('section_id = '. $id);

			$this->load->view('section', $this->data);
		}
	}

	public function order_ajax($project_id)
	{
		// Save order from ajax call
		if (isset($_POST['sortable'])) {
			$this->section_m->orderr($_POST['sortable']);
			// $this->section_m->save_order($_POST['sortable']);
		}
		
		// Fetch all pages
		$this->data['pages'] = $this->section_m->get_redacted($project_id);
		
		// Load view
		$this->load->view('order_ajax', $this->data);
	}
}
