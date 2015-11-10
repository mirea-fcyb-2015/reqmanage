<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$section->title ?></title>

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

	<script type='text/javascript' src="<?php echo site_url('js/js/jquery-1.10.2.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/jqueryui-1.10.3.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/bootstrap.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/enquire.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/jquery.cookie.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/jquery.nicescroll.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/codeprettifier/prettify.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/easypiechart/jquery.easypiechart.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/sparklines/jquery.sparklines.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/plugins/form-toggle/toggle.min.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/js/placeholdr.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/application.js'); ?>"></script> 
	<script type='text/javascript' src="<?php echo site_url('js/demo/demo.js'); ?>"></script> 


	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo site_url('css/styles.min.css'); ?>" />
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
			<? section_select($sections) ?>
		</ul>
	</nav>
	<div id="page-content">
		<div id="wrap">
			<div id="page-heading">
			    <ol class="breadcrumb">
			      <li><a href="<?=site_url('project') ?>">Главная</a></li>
			      <li><a href="<?=site_url('project/'. $section->project_id) ?>">Проект</a></li>
			      <li class="active"><?=$section->title; ?></li>
			    </ol>
				<h1><?=$section->title; ?></h1>
			</div>
			<div class="container">
			    <div class="panel panel-primary">
			    	<div class="panel-heading">Добавление требования</div>
			    		<div class="panel-body">
						<?php echo form_open(); ?>
						<div class="col-sm-6">
							<? echo form_input('req_title', NULL, 'class="form-control" placeholder="Название требования"'); ?>
						</div>
						<div class="col-sm-6">
							<? echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
						</div>
						<? echo form_close(); ?>
			    	</div>
			    </div>
			    <div class="panel panel-primary">
			    	<div class="panel-heading">Список требований</div>
		    		<div class="panel-body">
	    				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Удалить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? req_table($requirements); ?>
                            </tbody>
                        </table>
		    		</div>
			    </div>
			</div>
		</div>
	</div>
</div>

</body>
</html>