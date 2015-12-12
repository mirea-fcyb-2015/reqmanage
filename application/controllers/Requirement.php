<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requirement extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('project_m');
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
			if($this->input->post('attr_title')) {
				$data['req_id'] = $id;
				$data['title'] = $this->input->post('attr_title');
				if($this->input->post('attr_body'))
					$data['body'] = $this->input->post('attr_body');
				else 
					$data['body'] = NULL;

				$this->attribute_m->save_for_all($data);
			}

			$this->data['requirement'] = $this->requirement_m->get($id);
			$this->data['requirements'] = $this->requirement_m->get_by('section_id = '. $this->data['requirement']->section_id);
			$this->data['attributes'] = $this->attribute_m->get_by('req_id = '. $id);

			$section = $this->section_m->get_by('id = '. $this->data['requirement']->section_id, TRUE);
			$project = $this->project_m->get_by('id = '. $section->project_id, TRUE);

			$this->template->set_title($this->data['requirement']->title);
			$this->template->menu($this->data['requirements'], 'requirement');
			$this->template->breadcrumb(array($project->id, $project->title, $section->id, $section->title, $this->data['requirement']->title));
			$this->template->load_view('requirement', $this->data);
		}
	}

	public function delete($id)
	{
		$req = $this->requirement_m->get($id);
		$this->requirement_m->delete($id);

		// удаляем все атрибуты из этого требования
		$this->attribute_m->delete_by('req_id = '. $req->id);
		
		redirect('section/'. $req->section_id);
	}
}
