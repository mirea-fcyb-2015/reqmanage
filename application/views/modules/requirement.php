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
	<div class="panel-heading">Список аттрибутов</div>
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