<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('project_m');
		$this->load->model('section_m');
	}

	public function index($id = NULL)
	{
		if(!$id) {
			if($this->input->post('project_title')) {
				$data['title'] = $this->input->post('project_title');
				$data['created_at'] = time();
				$data['created_by'] = 0;

				$project_id = $this->project_m->save($data);
				$this->section_m->add_default($project_id);
			}

			$this->data['projects'] = $this->project_m->get();
			$this->load->view('projects', $this->data);
		}
		else {
			if($this->input->post('section_title')) {
				$data['title'] = $this->input->post('section_title');
				$data['project_id'] = $id;
				$data['parent_id'] = 0;

				$this->section_m->save($data);
			}

			$this->data['project'] = $this->project_m->get($id);
			// $this->data['sections'] = $this->section_m->get_by('project_id = '. $id);
			$this->data['sections'] = $this->section_m->get_redacted($id);
			$this->data['projects'] = $this->project_m->get();

			$this->load->view('project', $this->data);
		}
	}

}
