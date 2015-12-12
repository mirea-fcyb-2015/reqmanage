<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	public $what = 0; // cms_config/hierarchy

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

			$this->data['file_dir'] = './warehouse/project/'. $id;
			if(file_exists($this->data['file_dir']))
				$this->data['files'] = scandir($this->data['file_dir']);
			else 
				$this->data['file_dir'] = FALSE;

			$this->template->set_title($this->data['project']->title);
			$this->template->breadcrumb(array($this->data['project']->title));
			$this->template->menu($this->data['projects'], 'project');
			$this->template->load_view('project/main', $this->data);
		}
	}


	public function hierarchy($id = NULL)
	{
		$this->data['project'] = $this->project_m->get($id);

		if(empty($this->data['project']))
			show_404();

		$this->data['sections'] = $this->section_m->get_redacted($id);
		$this->data['projects'] = $this->project_m->get();

		$this->template->set_title($this->data['project']->title);
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->data['projects'], 'project');
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

			$this->change_m->add($this->what, $id, 'Изменена иерархия разделов ('. $project->title .')', $project->id);
		}
		
		$this->data['pages'] = $this->section_m->get_redacted($project_id);
		
		$this->load->view('order_ajax', $this->data);
	}

	public function delete($id)
	{
		// подрубаем модели, которые нужны
		$this->load->model(array('requirement_m', 'attribute_m'));

		$project = $this->project_m->get($id);
		if(empty($project))
			show_404();

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

		// удаляем проджект
		$this->project_m->delete($id);

		$this->change_m->add($this->what, $id, 'Удален проект ('. $project->title .')', $id);
		
		redirect('/');
	}

	public function description($id)
	{
		$this->data['project'] = $this->project_m->get($id);

		if(empty($this->data['project']))
			show_404();

		// сохраняем изменения в описании
		if($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
			$this->project_m->save($data, $id);

			$this->change_m->add($this->what, $id, 'Изменено описание проекта ('. $this->data['project']->title .')', $id);
		}

		// добавляем файлы
		if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
			// если нет такой директории, создать её
			if(!file_exists('warehouse/project/'. $id))
				mkdir('warehouse/project/'. $id);

			$file = $_FILES['file'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = $this->data['project']->created_at .'-'. time() .'.'. $ext;

			if(move_uploaded_file($file['tmp_name'], './warehouse/project/'. $id . '/' . basename($file['name'])))
				$this->data['message'] = 'Загружено!';
			else
				$this->data['message'] = 'Не удалось загрузить :(';

			$this->change_m->add($this->what, $id, 'Добавлен файл в описание проекта ('. $this->data['project']->title .')', $id);
		}

		$this->data['projects'] = $this->project_m->get();

		$this->template->add_js('trumbowyg');
		$this->template->add_js('load_editor');
		$this->template->add_css('trumbowyg.min');
		$this->template->set_title($this->data['project']->title);
		$this->template->breadcrumb(array($this->data['project']->title));
		$this->template->menu($this->data['projects'], 'project');
		$this->template->load_view('project/description', $this->data);
	}

	public function report($id = NULL)
	{
		if(!$id) {
			// показываем все последние отчеты
		}
		else {
			// выводим отчет и сохраняем его в файл на сервере
		}
	}
}
