<div class="panel panel-primary">
	<div class="panel-heading">Добавление атрибута</div>
		<div class="panel-body">
		<?php echo form_open(); ?>
		<div class="col-sm-4">
			<? echo form_input('attr_title', NULL, 'class="form-control" placeholder="Название аттрибута"'); ?>
		</div>
		<div class="col-sm-4">
			<? echo form_input('attr_body', NULL, 'class="form-control" placeholder="Содержание"'); ?>
		</div>
		<div class="col-sm-4">
			<? echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
		</div>
		<? echo form_close(); ?>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading"><h4>Атрибуты</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?=site_url('requirement/'. $requirement->id) ?>">Список атрибутов</a>
                </li>
                <li>
                    <a href="<?=site_url('requirement/description/'. $requirement->id) ?>">Редактировать</a>
                </li>
            </ul>
        </div>
    </div>
	<div class="panel-body">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Содержание</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php 
					foreach ($attributes as $r) {
						echo '<tr>
								<td>'. $r->title .'</td>
								<td>'. $r->body .'</td>
								<td>'. btn_delete('attribute/delete/'.$r->id, 'Вы хотите удалить атрибут, он удалится у всех требований этого раздела') .'</td>
							</tr>';
					}
				?>
            </tbody>
        </table>
	</div>
</div>
<?php if($requirement->description) { ?> 
<div class="panel panel-primary">
    <div class="panel-heading">Описание</div>
    <div class="panel-body">
        <?=$requirement->description ?>
    </div>
</div>
<? } ?>
<?php if($file_dir) { ?> 
<div class="panel panel-primary">
    <div class="panel-heading">Прикрепленные файлы</div>
    <div class="panel-body">
        <? file_table($files, $file_dir) ?>
    </div>
</div>
<? } ?>