<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('attribute_m');
	}

	public function index()
	{
		$this->load->view('welcome_message', $this->data);
	}

	public function order_ajax()
	{
		// Save order from ajax call
		if (isset($_POST['sortable'])) {
			$this->attribute_m->save_order($_POST['sortable']);
		}
		
		$this->data['attrs'] = $this->attribute_m->get_nested();
		
		$this->load->view('order_ajax', $this->data);
	}
}
