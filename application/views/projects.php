<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Список проектов</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?php echo site_url('js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
	<script src="<?php echo site_url('js/jquery.mjs.nestedSortable.js'); ?>"></script>


	<script type='text/javascript' src="<?php echo site_url('js/datatables/jquery.dataTables.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/TableTools.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.editor.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.editor.bootstrap.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.bootstrap.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/jquery.editable.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/demo-tableeditable.js'); ?>"></script> 

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo site_url('css/main.css'); ?>" />
    <link rel="stylesheet" href="<?php echo site_url('css/styles.min.css'); ?>" />
</head>
<body>

<div id="container">
	<h1>Список проектов</h1>

	<div class="half-block">
		<div class="block">
			<?php
				echo form_open();
					echo form_input('project_title', NULL, 'class="add-attr" placeholder="Название проекта"');
					echo form_submit('button', 'Создать', 'class="btn"');
				echo form_close();
			?>
		</div>
		<div class="block">
			<? project_list($projects); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>

</body>
</html>