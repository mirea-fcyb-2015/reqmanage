<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changes extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->data['changes'] = $this->change_m->get();
		$this->data['what'] = $this->config->item('hierarchy');

		$this->template->set_title('Последние изменения');
		$this->template->breadcrumb(array('Последние изменения'));
		$this->template->load_view('changes', $this->data);
	}
}
