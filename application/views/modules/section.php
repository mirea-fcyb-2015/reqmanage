<?php if(!isset($th)) { ?> 
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
<? } ?>
<div class="panel panel-primary">
	<div class="panel-heading">Список требований</div>
	<div class="panel-body">
        <?php if(isset($th)) { ?> 
            <div class="alert alert-info">
                Создайте новое требование или просто измените старое.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="crudtable">
            <thead>
                <tr>
                    <th>Название</th>
                    <?php
                    	foreach ($th as $t) {
                    		echo '<th>'. $t->title .'</th>';
                    	}
                    ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php } 
                else { ?>
            <div class="alert alert-info">
                Нет требований. Добавьте хотя бы одно. 
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <? } ?>
	</div>
</div>

<script type="text/javascript">
    
var editor;

$(function () {
    editor = new $.fn.dataTable.Editor({
        "ajaxURL":"<?=site_url('/section/table_source/'. $section->id) ?>",
        "ajax": function(type, xz, data, successCallback, errorCallback){
            $.ajax({
                "url":"<?=site_url('section/table_source/'. $section->id) ?>",
                "type": type,
                "data": data,
                "dataType": "json",
                "success": function (json) {
                    successCallback( json );
                },
                "error": function (xhr, error, thrown) {
                    errorCallback( xhr, error, thrown );
                }
            });
        },
        "idSrc": "id",
        "domTable":"#crudtable",
        "fields":[
            {
                "label":"id",
                "name":"id",
                "className":"hidden"
            },
            {
                "label":"Название",
                "name":"title"
            },
            <?php 
                foreach ($th as $t) {
                    echo '{ 
                            "label":"'. $t->title .'",
                            "name" :"'. transliterate($t->title) .'"
                        },';
                }
            ?>
        ]
    });

    $('#crudtable').dataTable({
        "sDom":"<'row'<'col-sm-6'T><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        "sAjaxSource":"<?=site_url('section/table_source/'. $section->id) ?>",
        "bServerSide": false,
        "bAutoWidth": true,
        "bDestroy": true,
        "aoColumns":[
            { "mData":"url" },
            <?php 
                foreach ($th as $t) {
                    echo '{ "mData":"'. transliterate($t->title) .'" },';
                }
            ?>
        ],
        "oTableTools":{
            "sRowSelect":"single",
            "aButtons":[
                { "sExtends":"editor_create", "editor":editor },
                { "sExtends":"editor_edit", "editor":editor },
                { "sExtends":"editor_remove", "editor":editor }
            ]
        }
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Поиск...');
    $('.dataTables_length select').addClass('form-control');

    //add icons
    $("#ToolTables_crudtable_0").prepend('<i class="fa fa-plus"/> ');
    $("#ToolTables_crudtable_1").prepend('<i class="fa fa-pencil-square-o"/> ');
    $("#ToolTables_crudtable_2").prepend('<i class="fa fa-times-circle"/> ');
});
</script>