<?php
class Project_m extends MY_Model
{
    protected $_primary_key = 'id';
    protected $_table_name = 'projects';
    protected $_order_by = 'created_at asc';

    public function get_new()
    {
        $model = new stdClass();
        $model->title = '';
        return $model;
    }

    public function add_moder($data)
    {
        $this->db->insert('users_projects', $data);
    }

    public function delete_moder($project_id, $user_id = NULL)
    {
        if($user_id)
            $this->db->where('user_id', $user_id);

        $this->db->where('project_id', $project_id);
        $this->db->delete('users_projects');
    }

    public function title_check($str)
    {
        $this->db->where('title', $str);
        $this->db->where('created_by', $this->user->get_user_id());
        $count = $this->db->count_all_results($this->_table_name);

        if($count > 0)
            return FALSE;
        else
            return TRUE;
    }
}
