<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('section_m');
		$this->load->model('requirement_m');
		$this->load->model('attribute_m');
	}

	public function index($id)
	{
		if($this->input->post('req_title')) {
			$data['section_id'] = $id;
			$data['title'] = $this->input->post('req_title');

			$this->requirement_m->save_with_attributes($data, $id);
		}

		$this->data['section'] = $this->section_m->get($id);
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

		$this->template->set_title($this->data['section']->title);
		$this->template->menu($sections, 'section', TRUE);
		$this->template->breadcrumb(array($this->data['section']->project_id, $project_title, $this->data['section']->title));
		$this->template->load_view('section', $this->data);
	}

	public function table_source($id)
	{
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
			}

			// если изменяем какое-то требование
			if($this->input->post('action') == 'edit') {
				$req_id = (int) $this->input->post('id');
				$recieved = $this->input->post('data');

				foreach ($recieved as $key => $value) {
					if($key == 'id') continue;

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
			}

			// если прощаемся с требованием
			if($this->input->post('action') == 'remove') {
				//$recieved[0] содержит id требования, которое нужно стереть с лица земли
				$recieved = $this->input->post('data');
				
				$req = $this->requirement_m->get($recieved[0]);
				$this->requirement_m->delete($recieved[0]);

				// удаляем все атрибуты из этого требования
				$this->attribute_m->delete_by('req_id = '. $req->id);
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
		$section = $this->section_m->get($id);

		if($section) {
			// удалить все требования и их атрибуты
			$requirements = $this->requirement_m->get_by('section_id = '. $section->id);
			foreach ($requirements as $r) {
				$this->requirement_m->delete($r->id);

				// удаляем все атрибуты из этого требования
				$this->attribute_m->delete_by('req_id = '. $r->id);
			}

			$this->section_m->delete($section->id);

			redirect('project/'. $section->project_id);
		}
	}
}
