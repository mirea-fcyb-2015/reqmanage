<div class="panel panel-primary">
	<div class="panel-heading">Добавление атрибута</div>
		<div class="panel-body">
		<?php echo form_open(); ?>
		<div class="col-sm-6">
			<? echo form_input('attr_title', NULL, 'class="form-control" placeholder="Название аттрибута"'); ?>
		</div>
		<div class="col-sm-6">
			<? echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
		</div>
		<? echo form_close(); ?>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Список аттрибутов</div>
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
                <? attr_table($attributes); ?>
            </tbody>
        </table>
	</div>
</div>