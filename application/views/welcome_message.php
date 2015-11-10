<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Редактирование атрибутов</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?php echo site_url('js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
	<script src="<?php echo site_url('js/jquery.mjs.nestedSortable.js'); ?>"></script>

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo site_url('css/main.css'); ?>" />
</head>
<body>

<div id="container">
	<h1>Редактирование атрибутов</h1>

	<div class="half-block">
		<div class="block">
			<p class="alert">Переместите атрибуты и нажмите "сохранить".</p>
			<div id="orderResult"></div>
			<input type="button" id="save" value="Сохранить" class="btn" />

			<script>
				$(function() {
					$.post('<?php echo site_url('order_ajax'); ?>', {}, function(data){
						$('#orderResult').html(data);
					});

					$('#save').click(function(){
						oSortable = $('.sortable').nestedSortable('toArray');

						$('#orderResult').slideUp(function(){
							$.post('<?php echo site_url('order_ajax'); ?>', { sortable: oSortable }, function(data){
								$('#orderResult').html(data);
								$('#orderResult').slideDown();
							});
						});
						
					});
				});
			</script>
		</div>
	</div>
	<div class="half-block">
		<div class="block">
			<?php
				echo form_open();
					echo form_input('new_attribute', 'атрибутик', 'class="add-attr"');
					echo form_submit('button', 'Добавить', 'class="btn"');
				echo form_close();
			?>
		</div>
	</div>
	<div class="clear"></div>
</div>

</body>
</html>