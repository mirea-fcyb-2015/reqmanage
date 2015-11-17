<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$project->title; ?></title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type='text/javascript' src="<?php echo site_url('js/js/jqueryui-1.10.3.min.js'); ?>"></script> 
	<script src="<?php echo site_url('js/jquery.mjs.nestedSortable.js'); ?>"></script>
	<script src="<?php echo site_url('js/jquery.nestable.js'); ?>"></script>

	<script type='text/javascript' src="<?php echo site_url('js/js/bootstrap.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/enquire.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/jquery.cookie.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/jquery.nicescroll.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/codeprettifier/prettify.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/easypiechart/jquery.easypiechart.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/sparklines/jquery.sparklines.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/form-toggle/toggle.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/placeholdr.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/application2.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/demo/demo.js'); ?>"></script> 

	<script type='text/javascript' src="<?php echo site_url('js/datatables/jquery.dataTables.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/TableTools.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.editor.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.editor.bootstrap.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/datatables/dataTables.bootstrap.js'); ?>"></script> 

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo site_url('css/styles.min.css'); ?>" />
	<link rel="stylesheet" href="<?php echo site_url('css/jquery.nestable.css'); ?>" />
</head>
<body>

<? $this->load->view('header') ?>

<div id="page-container">
	<nav id="page-leftbar" role="navigation">
        <ul class="acc-menu" id="sidebar">
            <li id="search">
                <a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
                <form>
                    <input type="text" class="search-query" placeholder="Поиск...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </li>
            <li><a href="<?=site_url() ?>"><i class="fa fa-home"></i> <span>Главная</span></a></li>
			<? project_menu($projects) ?>
		</ul>
	</nav>
	<div id="page-content">
		<div id="wrap">
			<div id="page-heading">
			    <ol class="breadcrumb">
			      <li><a href="<?=site_url('project') ?>">Главная</a></li>
			      <li class="active"><?=$project->title; ?></li>
			    </ol>
				<h1><?=$project->title; ?></h1>
			</div>
			<div class="container">
			    <div class="panel panel-primary">
			    	<div class="panel-heading">Добавить раздел</div>
		    		<div class="panel-body">
					<?php echo form_open(); ?>
						<div class="col-sm-6">
							<? echo form_input('section_title', NULL, 'class="form-control" placeholder="Название раздела"'); ?>
						</div>
						<div class="col-sm-6">
							<? echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
						</div>
						<? echo form_close(); ?>
		    		</div>
			    </div>
			    <div class="panel panel-primary">
			    	<div class="panel-heading">Список разделов</div>
		    		<div class="panel-body">
	    				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Название</th>
                                    <th>Количество требований</th>
                                    <th>Удалить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? sections_table($sections); ?>
                            </tbody>
                        </table>
                        <div id="order" class="dd"></div>
						<input type="button" id="save" value="Сохранить" class="btn-primary btn" />
<script>
$(function() {
	$.post('<?=site_url('section/order_ajax/'. $project->id); ?>', {}, function(data){
		$('#order').html(data);
	});

	$('.dd').nestable({ /* config options */ });

	$('#save').click(function(){
		// oSortable = $('.sortable').nestedSortable('toArray');

		$('.dd').slideUp(function(){
			$.post('<?=site_url('section/order_ajax/'. $project->id); ?>', {sortable : $('.dd').nestable('serialize') }, function(data){
				$('.dd').html(data);
				$('.dd').slideDown();
			});
		});
		
	});
});
</script>
		    		</div>
			    </div>
			</div>
		</div>
	</div>
</div>

</body>
</html>