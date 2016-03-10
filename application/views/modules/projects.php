<div class="panel panel-primary">
	<div class="panel-heading">Добавление проекта</div>
	<div class="panel-body">
		<?php
			echo form_open();
				echo '<div class="col-md-4">'. form_input('project_title', NULL, 'class="form-control" placeholder="Название проекта"') .'</div>';
				echo '<div class="col-md-4">'. form_submit('button', 'Создать', 'class="btn"') .'</div>';
			echo form_close();
		?>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Список ваших проектов</div>
	<div class="panel-body">
		<?php if(isset($error)) { ?>
	        <div class="alert alert-danger">
	            <?=$error ?>
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	        </div>
	    <? } ?>
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="crudtable">
	        <thead>
	            <tr>
	            	<th>Название</th>
	            	<th>Дата создания</th>
	            	<th>Дамп</th>
	            	<th>Удалить</th>
				</tr>
			</thead>
			<tbody>
                <?php
                	foreach ($projects as $p) {
                		echo '<tr>
                			<td><a href="'. site_url('project/'. $p->id) .'">'. $p->title .'</a></td>
							<td>'. ntime($p->created_at) .'</td>
                			<td>'. anchor('dump/make/'. $p->id, 'Загрузить') .'</td>
							<td><a href="'. site_url('project/delete/'. $p->id) .'">Удалить</a></td>
                		</tr>';
                	}
                ?>				
			</tbody>
		</table>
	</div>
</div>