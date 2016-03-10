<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['default_attributes'] = array(
    'Статус', 
    'Приоритет', 
    'Трудоёмкость', 
    'Стабильность', 
    'Целевая версия', 
    'Назначение'
    );

// набор стандартных требований
$config['default_sections'] = array(
    array(
        'title' => 'Функциональные требования',
        'is_functional' => TRUE
        ),
    array(
        'title' => 'Требования к надежности',
        'is_functional' => FALSE
        ), 
    array(
        'title' => 'Условия эксплуатации'
        ), 
    array(
        'title' => 'Требования к составу и параметрам технических средств'
        ),
    array(
        'title' => 'Требования к информационной и программной совместимости'
        ), 
    array(
        'title' => 'Требования к маркировке и упаковке'
        ),
    array(
        'title' => 'Требования к транспортированию и хранению'
        ),
    array(
        'title' => 'Специальные требования'
        )
    );

 $config['hierarchy'] = array(
    'project',
    'section',
    'requirement',
    'attribute'
    );