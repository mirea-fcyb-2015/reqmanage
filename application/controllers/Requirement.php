<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requirement extends MY_Controller {

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

        // следующий массив нужен для отображения списка всех разделов проекта        
        $this->data['sections_list'] = $this->section_m->get_all_sections_in_project($section->project_id, $section->id);

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

        // если нужно перенести требование в другой раздел
        if($this->input->post('move_to')) {
            $new_section_for_req = $this->input->post('move_to');

            if(!$this->user->check_section($new_section_for_req, $this->userProjects))
                show_error('У вас нет доступа к данному проекту.');

            $this->requirement_m->move($id, $new_section_for_req);
            redirect('requirement/'. $id);
        }

        // добавляем файлы
        if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
            // если нет такой директории, создать её
            if(!file_exists('warehouse/requirement/'. $id))
                mkdir('warehouse/requirement/'. $id);

            $file = $_FILES['file'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = pathinfo($file['name'], PATHINFO_FILENAME);
            $file['name'] = sha1(substr($this->data['requirement']->title, -6, 0) . time()) .'-'. $filename .'.'. $ext;

            if(move_uploaded_file($file['tmp_name'], './warehouse/requirement/'. $id . '/' . basename($file['name']))) {
                $this->change_m->add($this->what, $id, 'Добавлен файл к разделу ('. $file['name'] .')', $project->id);
                redirect('requirement/'. $id);
            }
            else {
                $this->data['message'] = 'Не удалось загрузить :(';
            }
        }

        $this->data['requirements'] = $this->requirement_m->get_by('section_id = '. $this->data['requirement']->section_id);
        $this->data['attributes'] = $this->attribute_m->get_by('req_id = '. $id);

        $this->data['file_dir'] = './warehouse/requirement/'. $id;
        if(file_exists($this->data['file_dir']))
            $this->data['files'] = scan_dir($this->data['file_dir']);
        else 
            $this->data['file_dir'] = FALSE;

        $this->template->set_title($this->data['requirement']->title);
        $this->template->set_menu($this->_set_menu(array(
            'project_id' => $project->id, 'project_title' => $project->title,
            'section_id' => $section->id, 'section_title' => $section->title,
            'id' => $id, 'title' => $this->data['requirement']->title)
        ));
        $this->template->set_breadcrumb(array($project->id, $project->title, $section->id, $section->title, $this->data['requirement']->title));
        $this->template->load_view('requirement/main', $this->data);
    }

    public function description($id)
    {
        // дополнительные модели
        $this->load->model('project_m');
        $this->load->model('section_m');

        $this->data['requirement'] = $this->requirement_m->get($id);

        if(empty($this->data['requirement']))
            show_404();

        // получаем родительскую секцию
        $section = $this->section_m->get_by('id = '. $this->data['requirement']->section_id, TRUE);

        // сохраняем изменения в описании
        if($this->input->post('description')) {
            $data['description'] = $this->input->post('description');
            $this->requirement_m->save($data, $id);

            $project = $this->project_m->get_by('id = '. $section->project_id, TRUE);
            $this->change_m->add($this->what, $id, 'Изменено описание требования ('. $this->data['requirement']->title .')', $project->id);
        }
        
        $project = $this->project_m->get_by('id = '. $section->project_id, TRUE);

        $this->template->add_js('trumbowyg');
        $this->template->add_js('load_editor');
        $this->template->add_css('trumbowyg.min');
        $this->template->set_title($this->data['requirement']->title);
        $this->template->set_menu($this->_set_menu(array(
            'project_id' => $project->id, 'project_title' => $project->title,
            'section_id' => $section->id, 'section_title' => $section->title,
            'id' => $id, 'title' => $this->data['requirement']->title)
        ));
        $this->template->set_breadcrumb(array($project->id, $project->title, $section->id, $section->title, $this->data['requirement']->title));
        $this->template->load_view('requirement/description', $this->data);
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


    private function _set_menu($array)
    {
        $menu =  array(
                    array('link' => 'project/'. $array['project_id'], 'title'=> $array['project_title'], 'divider' => TRUE),
                    array('link' => 'section/'. $array['section_id'], 'title'=> $array['section_title'], 'divider' => TRUE),
                    array('link' => 'requirement/'. $array['id'], 'title'=> $array['title']),
                    array('link' => 'requirement/description/'. $array['id'], 'title'=> '<i class="fa fa-angle-right"></i> Описание')
                );

        return $menu;
    }
}
