<?php
class MY_Controller extends CI_Controller {
	
	public $data = array();
		function __construct() {
			parent::__construct();
			
        	$this->load->library('ion_auth', NULL, 'user');
		}
}