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

    public function get_all_requirements($id)
    {
        $this->db->select('requirements.title')->from('sections')->where('project_id', $id)->join('requirements', 'requirements.section_id = sections.id');

        return $this->db->get()->result_array();
    }

    public function delete_from_matrix($id, $reqTitle)
    {
        // получаем матрицу
        $matrixOld = $this->get_matrix($id);

        if($matrixOld) {
            $matrix = json_decode($matrixOld->content, TRUE);
            
            // пробегаемся и сравниваем
            for($i = 0; $i < count($matrix); $i++) {
                if($matrix[0][$i] == $reqTitle) {
                    // стираем столбец с этим названием
                    for ($j = 0; $j < count($matrix); $j++) { 
                        unset($matrix[$j][$i]);
                    }
                }
            }

            // ищем теперь в первом столбце
            for ($i = 1; $i < count($matrix[0]); $i++) { 
                if($matrix[$i][0] == $reqTitle) {
                    // стираем строку с этим названием
                    unset($matrix[$i]);
                }
            }
            
            // сбрасываем индексы
            $matrix = array_values($matrix);
            $matrix = array_map('array_values', $matrix);
            for($i = 1; $i < count($matrix); $i++) {
                for($j = 1; $j < count($matrix); $j++) {
                    $matrix[$i][$j]['id'] = $i .'x'. $j;
                }
            }

            $this->db->where('project_id', $id);
            $this->db->update('matrix', array('content' => json_encode($matrix)));
        }
    }

    public function matrix_edit_title($id, $title, $oldTitle)
    {
        // получаем матрицу
        $matrixOld = $this->get_matrix($id);

        if($matrixOld) {
            $matrix = json_decode($matrixOld->content, TRUE);

            // не через foreach, потому что с ним херово редактируются данные
            // редактируем первую строчку
            for($i = 0; $i < count($matrix[0]); $i++) {
                if($matrix[0][$i] == $oldTitle)
                    $matrix[0][$i] = $title;
            }

            // изменяем в первом столбце
            for ($i = 1; $i < count($matrix); $i++) { 
                if($matrix[$i][0] == $oldTitle)
                    $matrix[$i][0] = $title;
            }

            $matrix = array_map('array_values', $matrix);
            $this->db->where('project_id', $id);
            $this->db->update('matrix', array('content' => json_encode($matrix)));
        }
    }

    public function add_to_matrix($id, $title)
    {
        // получаем матрицу
        $matrixOld = $this->get_matrix($id);

        if($matrixOld) {
            $matrix = json_decode($matrixOld->content, TRUE);
            
            // добавляем в конец первой строчки
            $matrix[0][] = $title;

            // для вычислений
            $count = count($matrix[0])-1;

            // добавляем в остальные строчки пустой столбец

            for ($i = 1; $i < count($matrix); $i++) { 
                $matrix[$i][] = array('_data' => '0', 'class' => 'xedit', 'id' => $i.'x'.$count, 'data-type' => 'text', 'data-placement' => 'top');
            }

            // добавляем пустую строчку в самый низ
            $tr = array();
            $tr[] = $title;
            for ($i = 1; $i <= $count; $i++) { 
                $tr[] = array('_data' => '0', 'class' => 'xedit', 'id' => $count.'x'.$i, 'data-type' => 'text', 'data-placement' => 'top');
            }
            $matrix[] = $tr;

            $this->db->where('project_id', $id);
            $this->db->update('matrix', array('content' => json_encode($matrix)));
        }
    }

    public function get_matrix($id)
    {
        $this->db->select()->from('matrix')->where('project_id', $id);
        return $this->db->get()->row();
    }

    public function save_matrix($id, $input_id, $input_data)
    {
        // получаем матрицу
        $ma = $this->get_matrix($id);
        $matrix = json_decode($ma->content, TRUE);

        list($i, $j) = explode('x', $input_id);
        
        $matrix[$i][$j]->_data = trim($input_data);

        $this->db->where('project_id', $id);
        $this->db->update('matrix', array('content' => json_encode($matrix)));
    }

    public function add_matrix($id, $requirements)
    {
        // будет содержать названия, которые потом будут в $martix[0]
        $titles = array();
        // первый элемент названий пустой
        $titles[] = '';
        // подсчет количества элементов
        $u = 1;
        foreach ($requirements as $r) {
            $titles[] = $r['title'];
            $u++;
        }

        // заполняем матрицу нулями и заменяем верхнюю строчку и первый столбец
        $matrix = array_fill(0, $u, array_fill(0, $u, 0));
        $matrix[0] = $titles;
        for($i = 1; $i < $u; $i++) {
            for($j = 0; $j < $u; $j++) {
                if($j == 0)
                    $matrix[$i][$j] = $titles[$i];
                else
                    $matrix[$i][$j] = array('_data' => '0', 'class' => 'xedit', 'id' => $i.'x'.$j, 'data-type' => 'text', 'data-placement' => 'top');
            }
        }

        $json = json_encode($matrix);

        $data = array('project_id' => $id, 'content' => $json);
        $this->db->insert('matrix', $data);

        return $json;
    }
}
