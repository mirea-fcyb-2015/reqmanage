<?php
class Change_m extends MY_Model
{
    protected $_primary_key = 'id';
    protected $_table_name = 'changes';
    protected $_order_by = 'when desc';
    
    public function add($what, $what_id, $description, $project_id = NULL)
    {
        if(!$project_id)
            $project_id = get_project_id($what, $what_id);

        if($what == 3)
            $what = 2; // чтобы ссылку потом сделать для атрибута

        $data = array(
            'what'        => $what,
            'what_id'     => $what_id,
            'who'         => $this->user->get_user_id(),
            'when'        => time(),
            'description' => $description,
            'project_id'  => $project_id
            );

        parent::save($data);
    }

    public function get_project_id($what, $what_id)
    {
        if($what == 3) {// атрибут
            // получаем req_id из сведений об атрибуте
            $req = $this->db->select('section_id')->from('attributes')->join('requirements', 'attributes.req_id = requirements.id')->where('attributes.id', $what_id)->get()->row();

            // чтобы уменьшить количество запросов
            $project = $this->db->select('id')->from('projects')->join('sections', 'sections.project_id = projects.id')->where('sections.id', $req->section_id)->get()->row();
        
            return $project->id;
        }
        elseif ($what == 2) {
            $req = $this->db->select('section_id')->from('requirements')->where('id', $what_id)->get()->row();
            $project = $this->db->select('id')->from('projects')->join('sections', 'sections.project_id = projects.id')->where('sections.id', $req->section_id)->get()->row();
            
            return $project->id;
        }
        else return 0;
    }

    public function count_changes($project_id)
    {
        $this->db->where('project_id', $project_id);

        return $this->db->count_all_results($this->_table_name);
    }
}
