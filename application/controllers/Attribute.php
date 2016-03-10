<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends MY_Controller {

    public $what = 3; // cms_config/hierarchy

    public function __construct(){
        parent::__construct();
        $this->load->model('requirement_m');
        $this->load->model('attribute_m');
    }

    public function delete($id)
    {
        $attribute = $this->attribute_m->get($id);

        if($attribute) {
            // получаем все требования из секции-родителя
            $requirements = $this->requirement_m->get_requirements_from_section_by_id($attribute->req_id);

            // пробегаемся по каждому требованию и удаляем атрибут с определенным тайтлом
            foreach ($requirements as $r) {
                $this->db->where('title', $attribute->title);
                $this->attribute_m->delete_by('req_id = '. $r->id);
            }

            $this->change_m->add($this->what, $attribute->req_id, 'Удален атрибут ('. $attribute->title .')');

            redirect('requirement/'. $attribute->req_id);
        }
    }
}
