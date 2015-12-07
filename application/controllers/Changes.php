<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
	}

	public function index()
	{
		echo 'table';
	}
}
