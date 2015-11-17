<?php

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

// function get_ol($array, $child = FALSE, $parent_level = '0')
// {
// 	$str = '';
// 	$level = 1;
// 	$original_pl = $parent_level;
	
// 	if (count($array)) {
// 		$str .= $child == FALSE ? '<ol class="sortable">' : '<ol>';
		
// 		foreach ($array as $item) {
// 			$str .= '<li id="list_' . $item['id'] .'">';
// 			if($parent_level == '0')
// 				$str .= '<div>'. $item['title'] .'</div>';
// 			else
// 				$str .= '<div>'. $item['title'] .'</div>';

// 			// Do we have any children?
// 			if (!empty($item['children'])) {
// 				if($parent_level != '0')
// 					$parent_level = $parent_level .'.'. $level;
// 				else
// 					$parent_level = $level;
// 				$str .= get_ol($item['children'], TRUE, $parent_level);
// 			}
// 			$parent_level = $original_pl;
			
// 			$str .= '</li>' . PHP_EOL;
// 			$level++;
// 		}
		
// 		$str .= '</ol>' . PHP_EOL;
// 	}
	
// 	return $str;
// }

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

function section_select(array $sections, $level = 0) 
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
			section_select($c['children'], $level + 1);

		echo '</li>';
	}

	if($level !== 0)
		echo '</ul>';
}

function project_menu($projects) 
{
	foreach ($projects as $p) {
		echo '<li><a href="'. site_url('project/'. $p->id) .'"><span>'. $p->title .'</span></a></li>';
	}
}

function req_menu($req) 
{
	foreach ($req as $p) {
		echo '<li><a href="'. site_url('requirement/'. $p->id) .'"><span>'. $p->title .'</span></a></li>';
	}
}

// function sections_table($sections)
// {
// 	foreach ($sections as $s) {
// 		echo '<tr>
// 				<td></td>
// 				<td>'. $s['title'] .'</td>
// 				<td>3</td>
// 				<td>Удалить</td>
// 			</tr>';
// 	}
// }

// function sections_table(array $sections, $level = 0)
// {
// 	foreach ($sections as $s) {
// 		echo '<tr>
// 				<td></td>
// 				<td><a href="'. site_url('section/'.$s['id']) .'">'. str_repeat('—', $level) .' '. $s['title'] .'</a></td>
// 				<td>3</td>
// 				<td>Удалить</td>
// 			</tr>';

//     	if (!empty($s['children']))
// 			sections_table($s['children'], $level + 1);
// 	}
// }

function sections_table(array $sections, $level = 0)
{
	foreach ($sections as $s) {
		echo '<tr>
				<td></td>
				<td><a href="'. site_url('section/'.$s['id']) .'">'. str_repeat('—', $level) .' '. $s['title'] .'</a></td>
				<td>3</td>
				<td><a href="'. site_url('requirement/delete/'. $s['id']) .'">Удалить</a>
			</tr>';

    	if (!empty($s['children']))
			sections_table($s['children'], $level + 1);
	}
}

function req_table($req)
{
	foreach ($req as $r) {
		echo '<tr>
				<td></td>
				<td><a href="'. site_url('requirement/'.$r->id) .'">'. $r->title .'</a></td>
				<td>'. $r->description .'</td>
				<td><a href="'. site_url('requirement/delete/'.$r->id) .'">Удалить</a></td>
			</tr>';
	}
}

function attr_table($attr)
{
	foreach ($attr as $r) {
		echo '<tr>
				<td></td>
				<td><a href="'. site_url('attribute/'.$r->id) .'">'. $r->title .'</a></td>
				<td>'. $r->body .'</td>
				<td><a href="'. site_url('attribute/delete/'.$r->id) .'">Удалить</a></td>
			</tr>';
	}
}