<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requirement extends CI_Controller {

	public $what = 2; // cms_config/hierarchy

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('requirement_m');
	}

	public function index($id)
	{
		// дополнительные модели
		$this->load->model('project_m');
		$this->load->model('section_m');
		$this->load->model('attribute_m');

		$this->data['requirement'] = $this->requirement_m->get($id);

		if(empty($this->data['requirement']))
			show_404();

		// получаем родительскую секцию и проект
		$section = $this->section_m->get_by('id = '. $this->data['requirement']->section_id, TRUE);
		$project = $this->project_m->get_by('id = '. $section->project_id, TRUE);

		// если добавляется атрибут
		if($this->input->post('attr_title')) {

			$data['req_id'] = $id;
			$data['title'] = $this->input->post('attr_title');
			if($this->input->post('attr_body'))
				$data['body'] = $this->input->post('attr_body');
			else 
				$data['body'] = NULL;

			$this->attribute_m->save_for_all($data);

			$this->change_m->add($this->what, $id, 'Добавлен атрибут ('. $data['title'] .')', $project->id);
		}

		$this->data['requirements'] = $this->requirement_m->get_by('section_id = '. $this->data['requirement']->section_id);
		$this->data['attributes'] = $this->attribute_m->get_by('req_id = '. $id);

		$this->data['file_dir'] = './warehouse/requirement/'. $id;
		if(file_exists($this->data['file_dir']))
			$this->data['files'] = scandir($this->data['file_dir']);
		else 
			$this->data['file_dir'] = FALSE;

		$this->template->set_title($this->data['requirement']->title);
		$this->template->menu($this->data['requirements'], 'requirement');
		$this->template->breadcrumb(array($project->id, $project->title, $section->id, $section->title, $this->data['requirement']->title));
		$this->template->load_view('requirement/main', $this->data);
	}

	public function delete($id)
	{
		$this->load->model('attribute_m');

		$req = $this->requirement_m->get($id);
		$this->requirement_m->delete($id);

		// удаляем все атрибуты из этого требования
		$this->attribute_m->delete_by('req_id = '. $req->id);
		
		$this->change_m->add($this->what, $id, 'Удалено требование ('. $req->title .')');

		redirect('section/'. $req->section_id);
	}

	public function description($id)
	{
		// дополнительные модели
		$this->load->model('project_m');
		$this->load->model('section_m');

		$this->data['requirement'] = $this->requirement_m->get($id);

		if(empty($this->data['requirement']))
			show_404();

		// получаем родительскую секцию и проект
		$section = $this->section_m->get_by('id = '. $this->data['requirement']->section_id, TRUE);
		$project = $this->project_m->get_by('id = '. $section->project_id, TRUE);

		// сохраняем изменения в описании
		if($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
			$this->requirement_m->save($data, $id);

			$this->change_m->add($this->what, $id, 'Изменено описание требования ('. $this->data['requirement']->title .')', $project->id);
		}

		// добавляем файлы
		if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
			// если нет такой директории, создать её
			if(!file_exists('warehouse/requirement/'. $id))
				mkdir('warehouse/requirement/'. $id);

			$file = $_FILES['file'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = sha1(substr($this->data['requirement']->title, 0, 10) .'-'. time()) .'.'. $ext;

			if(move_uploaded_file($file['tmp_name'], './warehouse/requirement/'. $id . '/' . basename($file['name']))) {
				$this->data['message'] = 'Загружено!';

				$this->change_m->add($this->what, $id, 'Добавлен файл в описание требования ('. $file['name'] .')', $project->id);
			}
			else
				$this->data['message'] = 'Не удалось загрузить :(';
		}


		$this->template->add_js('trumbowyg');
		$this->template->add_js('load_editor');
		$this->template->add_css('trumbowyg.min');
		$this->template->set_title($this->data['requirement']->title);
		$this->template->breadcrumb(array($project->id, $project->title, $section->id, $section->title, $this->data['requirement']->title));
		$this->template->load_view('requirement/description', $this->data);
	}
}
