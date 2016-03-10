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
    <div class="panel-heading">Добавление раздела</div>
    <div class="panel-body">
        <?php echo form_open(); ?>
        <div class="col-sm-4">
            <? echo form_input('section_title', NULL, 'class="form-control" placeholder="Название раздела"'); ?>
        </div>
        <div class="col-sm-4">
            <? echo form_dropdown('section_type', array('nonfunctional' => 'Только описание', 'functional' => 'С требованиями'), 'nonfunctional', 'class="form-control"'); ?>
        </div>
        <div class="col-sm-4">
            <? echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
        </div>
        <? echo form_close(); ?>
    </div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Список разделов</h4>
		<div class="options">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="<?=current_url() ?>">Информация</a>
				</li>
				<li>
					<a href="<?=site_url('project/hierarchy/'. $project->id) ?>">Иерархия</a>
				</li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
            <thead>
                <tr>
                    <th></th>
                    <th>Название</th>
                    <th>Количество требований</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php sections_table($sections); ?>
            </tbody>
        </table>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading"><h4>Описание проекта</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?php echo site_url('project/'. $project->id) ?>">Описание</a>
                </li>
                <li>
                    <a href="<?php echo site_url('project/description/'. $project->id) ?>">Редактировать</a>
                </li>
            </ul>
        </div>
    </div>
	<div class="panel-body">
            <?php if($project->description) { ?> 
                <?=$project->description ?>
            <?php } 
                else { ?>
            <div class="alert alert-info">
                Нет описания. <a href="<?php echo site_url('project/description/'. $project->id) ?>">Добавьте его. </a>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <? } ?>
	</div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">Прикрепленные файлы</div>
    <div class="panel-body">
        <?php if(isset($message)) { ?>
            <div class="alert alert-info">
                <?=$message ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <? } ?>
        <div class="panel">
            <div class="panel-body">
                <?php echo form_open_multipart(current_url(), 'class="form-horizontal"'); ?>
                <div class="col-md-4">
                    <?php echo form_upload(array('name' => 'file')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"'); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <?php if($file_dir)
            make_file_table($files, $file_dir); 
        ?>
    </div>
</div>