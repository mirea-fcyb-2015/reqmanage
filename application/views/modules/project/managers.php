<div class="row">
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-toyo" href="<?php echo site_url('project/report/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-download"></i></div>
				<div class="pull-right"><span class="badge">Отчет</span></div>
			</div>
			<div class="tiles-footer">
				ГОСТ 19.201
			</div>
		</a>
	</div>
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-orange" href="<?php echo site_url('project/changes/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-pencil"></i></div>
				<div class="pull-right"><span class="badge"><?php echo $changes_count; ?></span></div>
			</div>
			<div class="tiles-footer">
				Правки
			</div>
		</a>
	</div>
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-success" href="<?php echo site_url('project/managers/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-users"></i></div>
			</div>
			<div class="tiles-footer">
				Менеджеры проекта
			</div>
		</a>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-body">
		<?php if(isset($error_moder)) { ?>
	        <div class="alert alert-danger">
	            <?=$error_moder ?>
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	        </div>
	    <? } ?>
		<div class="col-md-12">
			<?php
				echo form_open(current_url(), 'class="form-horizontal"'); ?>
			<div class="form-group">
			<?	echo '<div class="col-md-4">'. form_input('user_email', NULL, 'class="form-control" placeholder="Почта пользователя"') .'</div>';
				echo '<div class="col-md-4">'. form_submit('button', 'Добавить к проекту', 'class="btn"') .'</div>';
				echo form_close(); ?>
			</div>
		</div>
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Имя</th>
						<th>Email</th>
						<th>Параметры</th>
					</tr>
				</thead>
				<tbody>
					<? 
						foreach ($project_managers as $p) {
							echo '<tr>';
							if($project->created_by == $p->id)
								echo '<td><i class="fa fa-star"></i> '. $p->first_name .' '. $p->last_name .'</td><td>'. $p->email .'</td><td></td>';
							else {
								echo '<td>'. $p->first_name .' '. $p->last_name .'</td><td>'. $p->email .'</td>';

								if($project->created_by == $this->user->get_user_id())
									echo '<td>'. btn_delete('project/delete_moder/'. $project->id .'/'. $p->id, 'Вы собираетесь удалить пользователя.') .'</td>';
								else
									echo '<td></td>';
							}


							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>