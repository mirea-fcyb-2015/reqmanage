<?php

if (!function_exists('assets_url')) {
	function assets_url($url)
	{
		return site_url('assets/'. $url);
	}
}

if (!function_exists('dump')) {
	function dump ($var, $label = 'Dump', $echo = TRUE)
	{
		// Store dump in variable
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		
		// Add formatting
		$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
		$output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
		
		// Output
		if ($echo == TRUE) {
			echo $output;
		}
		else {
			return $output;
		}
	}
}

// функция для почти перевода русских названий почти переменных
// stbtestbbb = съесть
function transliterate($string, $arrow = 0) {
    $roman = array("_", "bbb", "tbt", "Sch","sch",'Yo','Zh','Kh','Ts','Ch','Sh','Yu','ya','yo','zh','kh','ts','ch','sh','yu','ya','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','','Y','','E','a','b','v','g','d','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f','y','','e','c');
    $cyrillic = array(" ", "ь", "ъ", "Щ","щ",'Ё','Ж','Х','Ц','Ч','Ш','Ю','я','ё','ж','х','ц','ч','ш','ю','я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Ь','Ы','Ъ','Э','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','ы','ъ','э','к');
    
    if($arrow == 0)
    	return str_replace($cyrillic, $roman, $string);
    else 
    	return str_replace($roman, $cyrillic, $string);
}

function get_ol($array, $child = FALSE, $parent_level = '0')
{
	$str = '';
	$level = 1;
	$original_pl = $parent_level;
	
	if (count($array)) {
		$str .= $child == FALSE ? '<ol class="dd-list">' : '<ol class="dd-list">';
		
		foreach ($array as $item) {
			$str .= '<li id="list_' . $item['id'] .'" class="dd-item" data-id="' . $item['id'] .'">';
			$str .= '<div class="dd-handle dd3-handle"></div>
					<div class="dd3-content"><a href="'. site_url('section/'.$item['id']) .'">'. $item['title'] .'</a></div>';

			// Do we have any children?
			if (!empty($item['children'])) {
				if($parent_level != '0')
					$parent_level = $parent_level .'.'. $level;
				else
					$parent_level = $level;
				$str .= get_ol($item['children'], TRUE, $parent_level);
			}
			$parent_level = $original_pl;
			
			$str .= '</li>' . PHP_EOL;
			$level++;
		}
		
		$str .= '</ol>' . PHP_EOL;
	}
	
	return $str;
}

function project_list($projects)
{
	echo '<table class="table table-striped table-bordered datatables">';
	echo '<tr><th>Название</th><th>Delete</th></tr>';
	foreach ($projects as $p) {
		echo '<tr>
					<td class="editable"><a href="'. site_url('project/'. $p->id) .'">'. $p->title .'</a></td>
					<td><a href="'. site_url('project/delete/'. $p->id) .'">Удалить</a>
				</tr>';
	}
	echo '</table>';
}

function recursion_menu(array $sections, $level = 0) 
{
	$current_level = $level;
	foreach ($sections as $c) {
		if($current_level != 0) {
			echo '<ul class="acc-menu">';
			$current_level = 0;
		}

		if (!empty($c['children']))
			echo '<li><a href="javascript:;"><span>'. $c['title'] .'</span></a>';
		else
			echo '<li><a href="'. site_url('section/'. $c['id']) .'"><span>'. $c['title'] .'</span></a>';

    	if (!empty($c['children']))
			recursion_menu($c['children'], $level + 1);

		echo '</li>';
	}

	if($level !== 0)
		echo '</ul>';
}

function menu($items, $type, $recursion) 
{
	if(!$recursion) {
		foreach ($items as $p) {
			echo '<li><a href="'. site_url($type .'/'. $p->id) .'"><span>'. $p->title .'</span></a></li>';
		}
	}
	else {
		recursion_menu($items);
	}
}

function breadcrumb($breadcrumb)
{
	echo '<li><a href="'. site_url() .'">Главная</a></li>';
	if(isset($breadcrumb[0]))
		if(isset($breadcrumb[2]))
			echo '<li><a href="'. site_url('project/'. $breadcrumb[0]) .'">'. $breadcrumb[1] .'</a></li>';
		else
			echo '<li class="active">'. $breadcrumb[0] .'</li>';
	if(isset($breadcrumb[2]))    
		if(isset($breadcrumb[4]))  
			echo '<li><a href="'. site_url('section/'. $breadcrumb[2]) .'">'. $breadcrumb[3] .'</a></li>';
		else
			echo '<li class="active">'. $breadcrumb[2] .'</li>';
	if(isset($breadcrumb[4]))
		echo '<li class="active">'. $breadcrumb[4] .'</li>';
}

function sections_table(array $sections, $level = 0)
{
	foreach ($sections as $s) {
		echo '<tr>
				<td></td>
				<td><a href="'. site_url('section/'.$s['id']) .'">'. str_repeat('—', $level) .' '. $s['title'] .'</a></td>
				<td>'. $s['requirements'] .'</td>
				<td><a href="'. site_url('section/delete/'. $s['id']) .'">Удалить</a>
			</tr>';

    	if (!empty($s['children']))
			sections_table($s['children'], $level + 1);
	}
}

function req_table($req)
{ 
	// по тх сделать атрибуты, ищем их по ид требований ин проджект и делаем дистинкт
	foreach ($req as $r) {
		echo '<tr>
				<td><a href="'. site_url('requirement/'.$r->id) .'">'. $r->title .'</a></td>
				<td>'. $r->description .'</td>
				<td><a href="'. site_url('requirement/delete/'.$r->id) .'"><i class="fa fa-trash"></i></a></td>
			</tr>';
	}
}

// ============ DATE HELP ===========


function ntime($time)
{
	$date = date('Y-m-d', $time);
	list($year, $month, $day) = explode('-', $date);

	return $day .' '. month_short_name($month) .' '. $year;
}

function month_short_name($month) 
{
	$month = (int) $month;
	$m = array(' ', 'янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек');
	return $m[$month];
}


function btn_delete($uri, $confirmation_text)
{
	return anchor($uri, '<i class="fa fa-trash"></i>', array(
		'onclick' => "return confirm('$confirmation_text. Продолжить?');"
	));
}