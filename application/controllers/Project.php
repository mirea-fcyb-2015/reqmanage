<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

	public $what = 0; // cms_config/hierarchy

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('project_m');
		$this->load->model('section_m');
	}

	public function index($id = NULL)
	{
		$this->load->library('form_validation');

		if(!$id) {
			// создаем проект
			if($this->input->post('project_title')) {
				$this->form_validation->set_rules('project_title', 'project_title', 'trim|callback__project_title_check');
				if($this->form_validation->run()) {
					$data['title'] = $this->input->post('project_title');
					$data['created_at'] = time();
					$data['created_by'] = $this->user->get_user_id();

					$project_id = $this->project_m->save($data);
					$this->section_m->add_default($project_id);

					$this->user->add_project($project_id);
					redirect('', 'refresh');
				}
				else 
					$this->data['error'] = 'Название проекта не должно совпадать с названиями других ваших проектов.';
			}

			$this->data['projects'] = $this->project_m->get($this->user_projects);

			$this->template->breadcrumb(array());
			$this->template->set_title('Ваши проекты');
			$this->template->load_view('projects', $this->data);
		}
		else {
			if($this->input->post('section_title')) {
				$data['title'] = trim($this->input->post('section_title'));
				if($this->input->post('section_type') == 'nonfunctional')
					$data['is_functional'] = FALSE;
				elseif($this->input->post('section_type') == 'functional')
					$data['is_functional'] = TRUE;
				$data['project_id'] = $id;
				$data['parent_id'] = 0;

				$this->section_m->save($data);
				redirect(current_url(), 'refresh');
			}

			// содержание проекта 
			$this->data['project'] = $this->project_m->get($id);	

			// добавляем файлы
			if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
				// если нет такой директории, создать её
				if(!file_exists('warehouse/project/'. $id))
					mkdir('warehouse/project/'. $id);

				$file = $_FILES['file'];
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$filename = pathinfo($file['name'], PATHINFO_FILENAME);
				$file['name'] = sha1(substr($this->data['project']->title, -6, 0) . time()) .'-'. $filename .'.'. $ext;

				if(move_uploaded_file($file['tmp_name'], './warehouse/project/'. $id . '/' . basename($file['name']))) {
					$this->change_m->add($this->what, $id, 'Добавлен файл к проекту ('. $file['name'] .')', $id);
					redirect('project/'. $id);
				}
				else {
					$this->data['message'] = 'Не удалось загрузить :(';
				}
			}

			// список секций проекта
			$this->data['sections'] = $this->section_m->get_redacted($id);
			// подсчет изменений, внесенных в проект
			$this->data['changes_count'] = $this->change_m->count_changes($id);

			$this->data['file_dir'] = './warehouse/project/'. $id;
			if(file_exists($this->data['file_dir']))
				$this->data['files'] = scan_dir($this->data['file_dir']);
			else 
				$this->data['file_dir'] = FALSE;

			$this->template->set_title($this->data['project']->title);
			$this->template->breadcrumb(array($this->data['project']->title));
			$this->template->menu($this->_set_menu(array('id' => $id, 'title' => $this->data['project']->title)));
			$this->template->load_view('project/main', $this->data);
		}
	}

	public function homepage()
	{
		if($this->user->logged_in()) {
			redirect('project');
		}
		else {
			// показываем форму входа и все такое
			$this->template->set_base_view('horizontal_view');
			$this->template->set_layout('homepage');
			$this->template->load_view('homepage_guest');
		}
	}


	public function hierarchy($id)
	{
		$this->data['project'] = $this->project_m->get($id);
		$this->data['sections'] = $this->section_m->get_redacted($id);

		// подсчет изменений, внесенных в проект
		$this->data['changes_count'] = $this->change_m->count_changes($id);

		$this->template->set_title($this->data['project']->title);
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->_set_menu(array('id' => $id, 'title' => $this->data['project']->title)));
		$this->template->load_view('project/hierarchy', $this->data);
	}


	public function order_ajax($project_id)
	{
		// есть ли такой проект
		$project = $this->project_m->get($project_id);
		if(empty($project))
			show_404();

		// сохраняем иерархию требований
		if (isset($_POST['sortable'])) {
			$this->section_m->order($_POST['sortable']);

			$this->change_m->add($this->what, $project_id, 'Изменена иерархия разделов ('. $project->title .')', $project->id);
		}
		
		$this->data['pages'] = $this->section_m->get_redacted($project_id);
		
		$this->load->view('order_ajax', $this->data);
	}

	public function delete($id)
	{
		// подрубаем модели, которые нужны
		$this->load->model(array('requirement_m', 'attribute_m'));

		$project = $this->project_m->get($id);

		// удаляем все из секций
		$sections = $this->section_m->get_by('project_id = '. $project->id);

		foreach ($sections as $s) {
			// удалить все требования из секции и их атрибуты
			$requirements = $this->requirement_m->get_by('section_id = '. $s->id);
			foreach ($requirements as $r) {
				$this->requirement_m->delete($r->id);

				// удаляем все атрибуты из этого требования
				$this->attribute_m->delete_by('req_id = '. $r->id);
			}
		}

		// удаляем все секции проджекта
		$this->section_m->delete_by('project_id = '. $project->id);

		// удаляем историю изменений
		$this->change_m->delete_by('project_id = '. $project->id);

		// удаляем его у пользователей
		$this->change_m->delete_by('project_id = '. $project->id);

		// удаляем проджект
		$this->project_m->delete($id);

		$this->change_m->add($this->what, $id, 'Удален проект ('. $project->title .')', $id);
		
		redirect('/');
	}

	public function description($id)
	{
		$this->load->library(array('form_validation'));

		$this->data['project'] = $this->project_m->get($id);

		// сохраняем изменения в описании
		if($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
			$this->project_m->save($data, $id);

			$this->change_m->add($this->what, $id, 'Изменено описание проекта ('. $this->data['project']->title .')', $id);

			redirect('project/'. $id);
		}

		// подсчет изменений, внесенных в проект
		$this->data['changes_count'] = $this->change_m->count_changes($id);

		$this->template->add_js('trumbowyg');
		$this->template->add_js('load_editor');
		$this->template->add_css('trumbowyg.min');
		$this->template->set_title($this->data['project']->title);
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->_set_menu(array('id' => $id, 'title' => $this->data['project']->title)));
		$this->template->load_view('project/description', $this->data);
	}

	public function delete_moder($project_id, $user_id)
	{
		$project = $this->project_m->get($project_id);

		if($this->user->get_user_id() != $project->created_by)
			show_error('Вы не можете удалять пользователей.');

		if($user_id == $project->created_by)
			show_error('Вы не можете удалить себя из проекта, как бы этого не хотелось.');

		$this->project_m->delete_moder($project_id, $user_id);
		$this->change_m->add($this->what, $id, 'Удален модератор', $id);

		redirect('project/managers/'. $project_id);
	}

	public function managers($id)
	{
		$this->load->library(array('form_validation'));
		// добавляем модера в проект
		if($this->input->post('user_email')) {
			$this->form_validation->set_rules('user_email', 'email', 'valid_email');
			if($this->form_validation->run()) {
				if($this->user->check_user_email($this->input->post('user_email'))) {
					$data = array(
						'user_id' => $this->user->get_checked_user_id(),
						'project_id' => $id
						);
					$this->project_m->add_moder($data);

					$added_user = $this->user->user($this->user->get_checked_user_id())->row();
					$this->change_m->add($this->what, $id, 'Добавлен модератор ('. $added_user->email .')', $id);
				}
				else
					$this->data['error_moder'] = 'Нет пользователя с таким эмейлом';
			}
			else 
				$this->data['error_moder'] = 'Плохой эмейл';
		}

		$this->data['project'] = $this->project_m->get($id);
		$this->data['project_managers'] = $this->user->project_managers($id);
		// подсчет изменений, внесенных в проект
		$this->data['changes_count'] = $this->change_m->count_changes($id);

		$this->template->set_title('Менеджеры проекта "'. $this->data['project']->title .'"');
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->_set_menu(array('id' => $id, 'title' => $this->data['project']->title)));
		$this->template->load_view('project/managers', $this->data);
	}

	public function changes($id)
	{
		$this->data['changes'] = $this->change_m->get_by('project_id = '. $id);
		$this->data['what'] = $this->config->item('hierarchy');
		$this->data['project'] = $this->project_m->get($id);
		// подсчет изменений, внесенных в проект
		$this->data['changes_count'] = $this->change_m->count_changes($id);

		$this->template->set_title('Последние изменения');
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->_set_menu(array('id' => $id, 'title' => $this->data['project']->title)));
		$this->template->load_view('project/changes', $this->data);
	}

	public function report($id)
	{
		$this->load->model('requirement_m');

		if(!$id) {
			// показываем все последние отчеты
		}
		else {
			// выводим отчет и сохраняем его в файл на сервере
			$this->data['project'] = $this->project_m->get($id);

			// $this->db->where('is_functional', TRUE);
			$sections = $this->section_m->get_redacted($id);
			$sections_with_req = $this->_report_recursion($sections);

			$this->data['sections'] = $sections_with_req;

			$this->load->view('modules/project/report', $this->data);
		}
	}

	private function _report_recursion(array $sections, $parent_level = '', $level = 0)
	{	
		$section = array();
		$number = 1;
		foreach ($sections as $s) {
			$sec = array();
			$sec['id'] = $s['id'];
			$sec['title'] = $s['title'];
			$sec['description'] = $s['description'];
			if($level == 0)
				$sec['number'] = $number .'.';
			else
				$sec['number'] = $parent_level . $number .'.';

			if($s['is_functional']) {
				$requirements = $this->requirement_m->get_req_for_report($s['id']);

				$data = array();
				foreach ($requirements as $r) {
					$info = array();
					$info['id'] = $r['id'];
					$info['title'] = $r['title'];
					$info['description'] = $r['description'];

					foreach ($r['attributes'] as $a) {
						$info[transliterate($a['title'])] = $a['body'];
					}

					$data[] = $info;
					unset($info);
				}
				$sec['requirements'] = $data;
				unset($data);
			}

			if (!empty($s['children']))
				$sec['children'] = $this->_report_recursion($s['children'], $sec['number'], $level + 1);
			else
				$sec['children'] = array();
			
			$number++;

			$section[] = $sec;
			unset($sec);
		}

		return $section;
	}

	private function _set_menu($array)
	{
		$menu =  array(
					array('link' => 'project/'. $array['id'], 'title'=> $array['title']),
					array('link' => 'project/description/'. $array['id'], 'title'=> '<i class="fa fa-angle-right"></i> Описание'),
					array('link' => 'project/hierarchy/'. $array['id'], 'title'=> '<i class="fa fa-angle-right"></i> Иерархия'),
					array('link' => 'project/managers/'. $array['id'], 'title'=> '<i class="fa fa-angle-right"></i> Менеджеры'),
					array('link' => 'project/changes/'. $array['id'], 'title'=> '<i class="fa fa-angle-right"></i> Правки')
				);

		return $menu;
	}

	public function _project_title_check($str)
	{
		return $this->project_m->title_check($str);
	}
}
