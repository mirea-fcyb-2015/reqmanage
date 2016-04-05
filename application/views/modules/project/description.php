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
<div class="panel panel-gray">
	<div class="options">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=site_url('project/'. $project->id) ?>">К проекту</a>
			</li>
			<li class="active">
				<a href="<?=site_url('project/description/'. $project->id) ?>">Редактирование</a>
			</li>
		</ul>
	</div>
	<div class="panel-body">
		<?php echo form_open(null, 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название проекта</label>
            <div class="col-sm-8">
                <?php echo form_input('title', $project->title, 'class="form-control" placeholder="Название проекта"'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <?php echo form_textarea(array('name' => 'description'), $project->description, 'id="description"'); ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-2">
                <div class="btn-toolbar">
                    <?php echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"'); ?>
                </div>
            </div>
        </div>
    </div>
	<?php echo form_close(); ?>
</div>