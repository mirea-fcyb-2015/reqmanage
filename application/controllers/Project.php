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

			$this->template->breadcrumb(array());
			$this->template->set_title('Ваши проекты');
			$this->template->menu($this->data['projects'], 'project');
			$this->template->load_view('projects', $this->data);
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

			$this->template->set_title($this->data['project']->title);
			$this->template->breadcrumb(array($this->data['project']->title));
			$this->template->menu($this->data['projects'], 'project');
			$this->template->load_view('project/project', $this->data);
		}
	}


	public function delete($id)
	{

	}

	public function hierarchy($id = NULL)
	{
		if(!$id) {
			show_404();
		}
		else {
			$this->data['project'] = $this->project_m->get($id);
			// $this->data['sections'] = $this->section_m->get_by('project_id = '. $id);
			$this->data['sections'] = $this->section_m->get_redacted($id);
			$this->data['projects'] = $this->project_m->get();

			$this->template->set_title($this->data['project']->title);
			$this->template->breadcrumb(array($this->data['project']->title));
			$this->template->menu($this->data['projects'], 'project');
			$this->template->load_view('project/hierarchy', $this->data);
		}
	}

	public function order_ajax($project_id)
	{
		// Save order from ajax call
		if (isset($_POST['sortable'])) {
			$this->section_m->order($_POST['sortable']);
		}
		
		// Fetch all pages
		$this->data['pages'] = $this->section_m->get_redacted($project_id);
		
		// Load view
		$this->load->view('order_ajax', $this->data);
	}

	public function description($id = NULL)
	{
		if(!$id) {
			show_404();
		}
		else {
			$this->data['project'] = $this->project_m->get($id);
			$this->data['projects'] = $this->project_m->get();

			$this->template->set_title($this->data['project']->title);
			$this->template->breadcrumb(array($this->data['project']->title));
			$this->template->menu($this->data['projects'], 'project');
			$this->template->load_view('project/description', $this->data);
		}
	}
}
