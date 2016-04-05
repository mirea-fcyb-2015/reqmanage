<?php
class Requirement_m extends MY_Model
{
    protected $_primary_key = 'id';
    protected $_table_name = 'requirements';
    protected $_order_by = 'id asc';

    public $rules = array(
        'title' => array(
            'field' => 'title', 
            'label' => 'Title', 
            'rules' => 'trim|max_length[255]|required'
        ),
        'description' => array(
            'field' => 'description', 
            'label' => 'Description', 
            'rules' => 'trim|required'
        )
    );

    public function get_new()
    {
        $model = new stdClass();
        $model->title = '';
        $model->description = '';
        return $model;
    }

    public function get_by_array($where)
    {
        $this->db->where($where)->from($this->_table_name);

        return $this->db->get()->result_array();
    }

    public function save_with_attributes($data, $section_id)
    {
        // берем рандомное требование и смотрим, какие есть атрибуты
        $req = $this->db->select('id')->from('requirements')->where('section_id', $section_id)->get()->row();
        if($req)
            $attr = $this->db->select('title')->from('attributes')->where('req_id', $req->id)->get()->result();
        else
            $attr = $this->config->item('default_attributes');

        // сначала добавляем новое требование
        $this->db->insert('requirements', $data);
        $req_id = $this->db->insert_id();

        // а теперь добавляем ему атрибуты, какие есть у всех или просто дефолтные
        if($req) {
            foreach ($attr as $a) {
                $at['title'] = $a->title;
                $at['req_id'] = $req_id;
                $at['body'] = NULL;

                $this->db->insert('attributes', $at);
            }
        }
        else {
            foreach ($attr as $a) {
                $at['title'] = $a;
                $at['req_id'] = $req_id;
                $at['body'] = NULL;

                $this->db->insert('attributes', $at);
            }
        }
    }
    
    public function delete($id)
    {
        $this->db->where('req_id', $id);
        $this->db->delete('attributes');
        
        // Delete an atribute
        parent::delete($id);
    }

    public function get_attributes_title($req_id_array)
    {
        $this->db->distinct()->select('title')->where_in('req_id', $req_id_array);
        return $this->db->get('attributes')->result();
    }

    public function get_req_with_attributes($section_id)
    {
        $requirements = $this->db->select('id, title')->from('requirements')->where('section_id', $section_id)->get()->result_array();

        $new_requirements = array();
        foreach ($requirements as $r) {
            $attributes = $this->db->select('title, body')->where('req_id', $r['id'])->from('attributes')->get()->result_array();
            $r['attributes'] = $attributes;
            $new_requirements[] = $r;
            unset($r);
        }

        return $new_requirements;
    }
    
    // нужно для отчёта
    public function get_req_for_report($section_id)
    {
        $requirements = $this->db->select('id, title, description')->from('requirements')->where('section_id', $section_id)->get()->result_array();

        $new_requirements = array();
        foreach ($requirements as $r) {
            $attributes = $this->db->select('title, body')->where('req_id', $r['id'])->from('attributes')->get()->result_array();
            $r['attributes'] = $attributes;
            $new_requirements[] = $r;
            unset($r);
        }

        return $new_requirements;
    }

    // функция переноса требования в другой раздел
    public function move($requirement_id, $section_id) 
    {
        $requirement = parent::get($requirement_id);

        $data['section_id'] = $section_id;
        $this->db->set($data)->where($this->_primary_key, $requirement_id)->update($this->_table_name);

        $this->load->model('section_m');
        $this->section_m->set_functional($section_id);
    }
}
