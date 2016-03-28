<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dump extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    {
        if(isset($_FILES['file']) && $_FILES['file']['size'] > 0) 
        {
            $this->load->model(array('attribute_m', 'requirement_m', 'section_m', 'project_m'));
            if($file = file_get_contents($_FILES['file']['tmp_name'])) {
                // на проверку пофиг, Антон хочет носить это все на флешке => злоумышленников для него нету
                $base = json_decode($file, TRUE);
                // dump($base);

                if(!$this->project_m->title_check($base['project']['title']))
                    $base['project']['title'] = $base['project']['title'] .' ver. '. date('Y-m-d h:i:s');
              
                // вставляем проект с небольшими правками в БД
                $base['project']['created_by'] = $this->user->get_user_id();
                unset($base['project']['id']);
                $project_id = $this->project_m->save($base['project']);

                // секции
                // для них приходится делать двумерный массив, хранящий старые и новые id
                $sections_id = array(array(), array());
                $i = 0;
                foreach ($base['sections'] as $k => $s) {
                    $s['project_id'] = $project_id;
                    $sections_id[0][$i] = $s['id']; // здесь старый ид
                    unset($s['id']);
                    $sec_id = $this->section_m->save($s);
                    $sections_id[1][$i] = $sec_id; // здесь новый ид
                    $i++;

                    // требования
                    if(!empty($base['requirements'][$k])) {
                        foreach ($base['requirements'][$k] as $kr => $r) {
                            $r['section_id'] = $sec_id;
                            $old_req_id = $r['id'];
                            unset($r['id']);
                            $req_id = $this->requirement_m->save($r);

                            // атрибуты
                            foreach ($base['attributes'] as $attr) {
                                if($attr[0]['req_id'] == $old_req_id) {
                                    foreach ($attr as $a) {
                                        unset($a['id']);
                                        $a['req_id'] = $req_id;

                                        $this->attribute_m->save($a);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }

                // теперь делаем норм parent_id у разделов
                foreach ($sections_id[1] as $sid) {
                    $section = $this->section_m->get($sid);

                    if($section->parent_id != 0) {
                        foreach ($sections_id[0] as $k => $v) {
                            if($section->parent_id == $v) {
                                $data = array('parent_id' => $sections_id[1][$k]);
                                $this->section_m->save($data, $sid);
                            }
                        }
                    }
                }
                $this->db->insert('users_projects', array('user_id' => $this->user->get_user_id(), 'project_id' => $project_id));

                redirect('project/'. $project_id);
            }
            else {
                $this->data['message'] = 'Не удалось загрузить :(';
            }
        }

        $this->template->set_title('Импорт');
        $this->template->set_breadcrumb(array('Импорт'));
        $this->template->load_view('import', $this->data);
    }

    public function make($project_id)
    {
        $this->load->model(array('attribute_m', 'requirement_m', 'section_m', 'project_m'));

        $dump = array();

        $dump['project'] = $this->project_m->get($project_id);
        $dump['sections'] = $this->section_m->get_by('project_id = '. $project_id);
        
        foreach ($dump['sections'] as $s) {
            $dump['requirements'][] = $this->requirement_m->get_by('section_id = '. $s->id);
        }

        foreach ($dump['requirements'] as $r) {
            foreach ($r as $req) {
                $dump['attributes'][] = $this->attribute_m->get_by('req_id = '. $req->id);
            }
        }

        // заголовки для открытия окна загрузки файла
        header('Content-disposition: attachment; filename='. transliterate($dump['project']->title) .'.rmdump');
        header('Content-type: application/json');
        echo json_encode($dump);
    }
}
