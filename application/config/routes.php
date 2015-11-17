<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['order_ajax'] = 'welcome/order_ajax';

$route['project/(:num)'] = 'project/index/$1';
$route['section/(:num)'] = 'section/index/$1';
$route['requirement/(:num)'] = 'requirement/index/$1';
$route['auth'] = 'auth';