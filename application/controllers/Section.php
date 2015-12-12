<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends CI_Controller {

	public $what = 1; // cms_config/hierarchy

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('section_m');
	}

	public function index($id)
	{
		// дополнительная модель
		$this->load->model('requirement_m');

		$this->data['section'] = $this->section_m->get($id);

		if(empty($this->data['section']))
			show_404();

		if($this->input->post('req_title')) {
			$data['section_id'] = $id;
			$data['title'] = $this->input->post('req_title');

			$this->requirement_m->save_with_attributes($data, $id);

			$this->change_m->add($this->what, $id, 'Добавлено требование ('. $data['title'] .')', $this->data['section']->project_id);
		}

		$this->data['requirements'] = $this->requirement_m->get_req_with_attributes($id);
		if($this->data['requirements']) {
			$req_ids = array();
			foreach ($this->data['requirements'] as $req) {
				$req_ids[] = $req['id'];
			}

			$this->data['th'] = $this->requirement_m->get_attributes_title($req_ids);
		}
		
		$sections = $this->section_m->get_redacted($this->data['section']->project_id);
		$project_title = $this->section_m->get_project_title($this->data['section']->project_id);

		$this->data['file_dir'] = './warehouse/section/'. $id;
		if(file_exists($this->data['file_dir']))
			$this->data['files'] = scandir($this->data['file_dir']);
		else 
			$this->data['file_dir'] = FALSE;

		$this->template->set_title($this->data['section']->title);
		$this->template->menu($sections, 'section', TRUE);
		$this->template->breadcrumb(array($this->data['section']->project_id, $project_title, $this->data['section']->title));
		$this->template->load_view('section/main', $this->data);
	}

	public function table_source($id)
	{
		// проверка есть ли такой раздел
		$section = $this->section_m->get($id);
		if(empty($section))
			show_404();

		// дополнительные модели
		$this->load->model('attribute_m');
		$this->load->model('requirement_m');

		// в функции можно сделать проверки не обращаясь к sql-серверу, но чет лень
		// и еще можно всякие проверки сделать
		// но чет лень
		$error = '';

		if($_POST) {
			// если создаем новое требование
			if($this->input->post('action') == 'create') {
				$data = $this->input->post('data');

				// добавляем требование
				$requirement['section_id'] = (int) $id;
				$requirement['title'] = $data['title'];
				$insert_id = $this->requirement_m->save($requirement);
				unset($data['title']);

				// добавляем атрибуты
				foreach ($data as $key => $value) {
					if($key == 'id') continue;

					$attribute['req_id'] = $insert_id;
					$attribute['title'] = transliterate($key, 1);
					if(empty($value))
						$attribute['body'] = NULL;
					else
						$attribute['body'] = $value;

					$this->attribute_m->save($attribute);
				}

				$this->change_m->add($this->what, $id, 'Добавлено требование ('. $requirement['title'] .')', $section->project_id);
			}

			// если изменяем какое-то требование
			if($this->input->post('action') == 'edit') {
				$req_id = (int) $this->input->post('id');
				$recieved = $this->input->post('data');

				foreach ($recieved as $key => $value) {
					if($key == 'id') continue;
					if($key == 'url') continue;

					if($key == 'title') {
						$this->requirement_m->save(array('title' => $value), $req_id);
						continue;
					}

					$attribute['title'] = transliterate($key, 1);
					if(empty($value))
						$attribute['body'] = NULL;
					else
						$attribute['body'] = $value;

					$where = array('title' => $attribute['title'], 'req_id' => $req_id);
					$attr = $this->attribute_m->get_by($where, TRUE);

					$this->attribute_m->save($attribute, $attr->id);
				}

				$this->change_m->add($this->what, $id, 'Изменены атрибуты требования ('. $recieved['title'] .')', $section->project_id);
			}

			// если прощаемся с требованием
			if($this->input->post('action') == 'remove') {
				//$recieved[0] содержит id требования, которое нужно стереть с лица земли
				$recieved = $this->input->post('data');
				
				$req = $this->requirement_m->get($recieved[0]);
				$this->requirement_m->delete($recieved[0]);

				// удаляем все атрибуты из этого требования
				$this->attribute_m->delete_by('req_id = '. $req->id);

				$this->change_m->add($this->what, $id, 'Удалено требование ('. $req->title .')', $section->project_id);
			}
		}

		$requirements = $this->requirement_m->get_req_with_attributes($id);

		$data = array();
		foreach ($requirements as $r) {
			$info = array();
			$info['id'] = $r['id'];
			$info['title'] = $r['title'];
			$info['url'] = '<a href="'. site_url('requirement/'. $r['id']) .'">'. $r['title'] .'</a>';

			foreach ($r['attributes'] as $a) {
				$info[transliterate($a['title'])] = $a['body'];
			}

			$data[] = $info;
		}

		$array = array(
				'id' => -1,
				'error' => $error,
				'fieldErrors' => array(),
				'data' => array(),
				'aaData' => $data
			);

		echo json_encode($array);
	}

	public function delete($id)
	{
		// проверка есть ли такой раздел
		$section = $this->section_m->get($id);
		if(empty($section))
			show_404();

		// дополнительные модели
		$this->load->model('attribute_m');
		$this->load->model('requirement_m');

		// удалить все требования и их атрибуты
		$requirements = $this->requirement_m->get_by('section_id = '. $section->id);
		foreach ($requirements as $r) {
			$this->requirement_m->delete($r->id);

			// удаляем все атрибуты из этого требования
			$this->attribute_m->delete_by('req_id = '. $r->id);
		}

		$this->section_m->delete($section->id);

		$this->change_m->add($this->what, $id, 'Удален раздел ('. $section->title .')', $section->project_id);

		redirect('project/'. $section->project_id);
	}

	public function description($id)
	{
		$this->data['section'] = $this->section_m->get($id);

		if(empty($this->data['section']))
			show_404();

		// сохраняем изменения в описании
		if($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
			$this->section_m->save($data, $id);

			$this->change_m->add($this->what, $id, 'Изменено описание раздела ('. $this->data['section']->title .')', $this->data['section']->project_id);
		}

		// добавляем файлы
		if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
			// если нет такой директории, создать её
			if(!file_exists('warehouse/section/'. $id))
				mkdir('warehouse/section/'. $id);

			$file = $_FILES['file'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = sha1(substr($this->data['section']->title, 0, 10) .'-'. time()) .'.'. $ext;

			if(move_uploaded_file($file['tmp_name'], './warehouse/section/'. $id . '/' . basename($file['name'])))
				$this->data['message'] = 'Загружено!';
			else
				$this->data['message'] = 'Не удалось загрузить :(';

			$this->change_m->add($this->what, $id, 'Добавлен файл к разделу ('. $file['name'] .')', $this->data['section']->project_id);
		}

		$project_title = $this->section_m->get_project_title($this->data['section']->project_id);

		$this->template->add_js('trumbowyg');
		$this->template->add_js('load_editor');
		$this->template->add_css('trumbowyg.min');
		$this->template->set_title($this->data['section']->title);
		$this->template->breadcrumb(array($this->data['section']->project_id, $project_title, $this->data['section']->title));
		$this->template->load_view('section/description', $this->data);
	}
}
