<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'project/homepage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['project/(:num)'] = 'project/index/$1';
$route['section/(:num)'] = 'section/index/$1';
$route['requirement/(:num)'] = 'requirement/index/$1';