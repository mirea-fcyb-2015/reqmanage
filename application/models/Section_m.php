<?php
class Section_m extends MY_Model
{
    protected $_primary_key = 'id';
    protected $_table_name = 'sections';
    protected $_order_by = 'order asc, id asc';

    public $rules = array(
        'title' => array(
            'field' => 'title', 
            'label' => 'Title', 
            'rules' => 'trim|max_length[256]|required'
        )
    );

    public function get_new()
    {
        $model = new stdClass();
        $model->title = '';
        return $model;
    }
    
    public function delete($id)
    {
        // удаляем раздел
        parent::delete($id);
        
        // сбрасываем айди родителя у его детей
        $this->db->set(array(
            'parent_id' => 0
        ))->where('parent_id', $id)->update($this->_table_name);
    }

    public function add_default($project_id)
    {
        $sections = $this->config->item('default_sections');

        foreach ($sections as $s) {
            $data['title'] = $s['title'];
            $data['project_id'] = $project_id;
            $data['parent_id'] = 0;

            if(isset($s['is_functional']) && $s['is_functional'] == TRUE)
                $data['is_functional'] = TRUE;

            parent::save($data);
            unset($data);
        }
    }

    public function count_requirements($id)
    {
        $this->db->select('id')->from('requirements')->where('section_id', $id);
        return $this->db->count_all_results();
    }

    public function get_redacted($project_id)
    {
        $this->db->select()->from('sections')->where('project_id', $project_id)->order_by($this->_order_by);
        $result = $this->db->get()->result_array();

        $sections = array();
        foreach($result as $r) {
            $r['children'] = array();
            if($r['is_functional'])
                $r['requirements'] = $this->count_requirements($r['id']);
            else
                $r['requirements'] = '';
            $sections[$r['id']] = $r;
        }

        // создаем иерархию с ссылками внутри массива
        foreach ($sections as $c => &$v) {
            if ($v['parent_id'] != 0) {
                $sections[$v['parent_id']]['children'][] =& $v;
            }
        }
        unset($v);

        // удаляем всякий мусор c первого уровня со знаком &
        foreach ($sections as $c => $v) {
            if ($v['parent_id'] != 0)
                unset($sections[$c]);
        }

        return $sections;
    }

    public function order($sections, $parent_id = 0, $order = 1)
    {
        if (count($sections)) {
            // dump($pages);
            foreach ($sections as $s) {
                $data = array(
                    'parent_id' => $parent_id,
                    'order' => $order
                    );
                $this->db->set($data)->where($this->_primary_key, $s['id'])->update($this->_table_name);
                $order++;

                if(!empty($s['children']))
                    $this->order($s['children'], $s['id'], $order);
            }
        }
    }

    public function delete_from_matrix($id, $reqTitle)
    {
        // получаем матрицу
        // $ma = $this->get_matrix($id);

        // if($ma) {
        //     $matrix = json_decode($ma->content);
            
        //     // не через foreach, потому что с ним херово редактируются данные
        //     // редактируем первую строчку
        //     for($i = 0; $i < count($matrix[0]); $i++) {
        //         if($matrix[0][$i] == $oldTitle)
        //             $matrix[0][$i] = $title;
        //     }

        //     // изменяем в первом столбце
        //     for ($i = 1; $i < count($matrix); $i++) { 
        //         if($matrix[$i][0] == $oldTitle)
        //             $matrix[$i][0] = $title;
        //     }

        //     $this->db->where('section_id', $id);
        //     $this->db->update('matrix', array('content' => json_encode($matrix)));
        // }
    }

    public function matrix_edit_title($id, $title, $oldTitle)
    {
        // получаем матрицу
        $ma = $this->get_matrix($id);

        if($ma) {
            $matrix = json_decode($ma->content);
            
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

            $this->db->where('section_id', $id);
            $this->db->update('matrix', array('content' => json_encode($matrix)));
        }
    }

    public function add_to_matrix($id, $title)
    {
        // получаем матрицу
        $ma = $this->get_matrix($id);

        if($ma) {
            $matrix = json_decode($ma->content);
            
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

            $this->db->where('section_id', $id);
            $this->db->update('matrix', array('content' => json_encode($matrix)));
        }
    }

    public function get_matrix($id)
    {
        $this->db->select()->from('matrix')->where('section_id', $id);
        return $this->db->get()->row();
    }

    public function save_matrix($id, $input_id, $input_data)
    {
        // получаем матрицу
        $ma = $this->get_matrix($id);
        $matrix = json_decode($ma->content);

        list($i, $j) = explode('x', $input_id);
        
        $matrix[$i][$j]->_data = trim($input_data);

        $this->db->where('section_id', $id);
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
            $titles[] = $r->title;
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

        $data = array('section_id' => $id, 'content' => $json);
        $this->db->insert('matrix', $data);

        return $json;
    }
}
