<?php
class MY_Controller extends CI_Controller {
	
	public $data = array();
	// список проектов пользователя
	public $userProjects = array();

	function __construct() {
		parent::__construct();
		
    	$this->load->library('ion_auth', NULL, 'user');

    	// проверка на может ли пользователь что-то делать
    	// если он админ, то он бог, поэтому проверяем не админов
    	if(!$this->user->is_admin()) {
    		if($this->user->logged_in()) {
    			// получаем список проектов пользователя
    			$this->userProjects = $this->user->projects();

	    		// получаем местонахождение на сайте
	    		$controller = $this->uri->segment(1);
	    		// это может быть метод или id
	    		$id = $this->uri->segment(2); 

	    		// смотрим всякое
	    		if($controller == 'project') {
	    			if(ctype_digit($id)) { // если это не метод, а айди
	    				if(!search_for_id($id, $this->userProjects)) // если данного айди нет в списке пользователя
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    			elseif($this->uri->segment(3)) {
	    				// раз это метод, то нам нужен следующий кусок
	    				$id = $this->uri->segment(3);
	    				if(!search_for_id($id, $this->userProjects)) // если данного айди нет в списке пользователя
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    		}
	    		elseif($controller == 'section') {
	    			// чтоб снизить нагрузку, нужно просто узнать к какому проекту принаждежит раздел и проверить
	    			// для этого есть функция check_section

	    			if(ctype_digit($id)) {
	    				if(!$this->user->check_section($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    			else {
	    				$id = $this->uri->segment(3);
	    				if(!$this->user->check_section($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    		}
	    		elseif($controller == 'requirement') {
	    			if(ctype_digit($id)) {
	    				if(!$this->user->check_requirement($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    			else {
	    				$id = $this->uri->segment(3);
	    				if(!$this->user->check_requirement($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    		}
	    		elseif($controller == 'attribute') {
	    			if(ctype_digit($id)) {
	    				if(!$this->user->check_attribute($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    			else {
	    				$id = $this->uri->segment(3);
	    				if(!$this->user->check_attribute($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
					}
	    		}
	    		elseif($controller == 'dump') {
	    			if(ctype_digit($id)) {
	    				if(!search_for_id($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
	    			}
	    			elseif($this->uri->segment(3)) {
	    				$id = $this->uri->segment(3);
	    				if(!search_for_id($id, $this->userProjects))
							show_error('У вас нет доступа к данному проекту.');
					}
	    		}
    		}
    		else {
    			if($this->uri->segment(1) != 'users' && $this->uri->segment(1))
    				show_error('Чтобы просматривать эту страницу, вы должны войти.');
    		}

    	}
    	else {
    		$this->userProjects = $this->user->projects();
    	}
	}
}
