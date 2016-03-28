<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php echo $metadata; ?>

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link href="<?=assets_url('css/styles.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?=assets_url('css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?=assets_url('css/dataTables.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?=assets_url('css/jquery.nestable.css') ?>" rel="stylesheet" type="text/css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
        
        <?php echo $css; ?>

        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="<?=assets_url('js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/jquery.jsontotable.min.js'); ?>"></script> 

        <!--[if lt IE 9]>
            <script src="<?php echo assets_url('js/html5shiv.min.js'); ?>"></script>
            <script src="<?php echo assets_url('js/respond.min.js'); ?>"></script>
        <![endif]-->
    </head>
    <body>
        <?php echo $body; ?>

        <!-- Extra javascript -->
        <?php echo $js; ?>
        <!-- / -->
        
        <script type='text/javascript' src="<?php echo assets_url('js/bootstrap-treeview.min.js'); ?>"></script>
            
        <script type='text/javascript' src="<?php echo assets_url('js/datatables/jquery.dataTables.min.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/datatables/dataTables.bootstrap.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/datatables/TableTools.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/datatables/dataTables.editor.js'); ?>"></script> 
        <script type='text/javascript' src="<?php echo assets_url('js/datatables/dataTables.editor.bootstrap.js'); ?>"></script>

        <script type='text/javascript' src="<?php echo assets_url('js/jquery.mjs.nestedSortable.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/jquery.nestable.js'); ?>"></script>

        <script type='text/javascript' src="<?php echo assets_url('js/bootstrap.min.js'); ?>"></script> 
        <script type='text/javascript' src="<?php echo assets_url('js/enquire.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/jquery.nicescroll.min.js'); ?>"></script>
        <script type='text/javascript' src="<?php echo assets_url('js/placeholdr.js'); ?>"></script> 
        <script type='text/javascript' src="<?php echo assets_url('js/bootstrap-editable.min.js'); ?>"></script> 
        <script type='text/javascript' src="<?php echo assets_url('js/application.js'); ?>"></script> 
    </body>
</html>