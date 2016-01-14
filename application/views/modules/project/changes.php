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
<div class="panel">
	<div class="panel-heading">
		Последние изменения в проекте "<?php echo $project->title; ?>"
	</div>
    <div class="panel-body">
    	<?php 
    		if(!empty($changes)) { ?>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
	            <thead>
					<tr>
						<th>Изменение</th>
						<th>Автор изменения</th>
						<th>Дата</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($changes as $c):?>
						<? $user = $this->user->user($c->who)->row(); ?>
						<tr>
				            <td><?=anchor($what[$c->what] .'/'. $c->what_id, $c->description) ?></td>
				            <td><? echo $user->first_name .' '. $user->last_name ?></td>
				            <td><?=date('d.m.Y H:i', $c->when) ?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php } 
			else { ?>
			<div class="alert alert-dismissable alert-warning">
				<strong>Внимание!</strong> В проекте не было никаких изменений.
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			</div>
		<?php } ?>
	</div>
</div>