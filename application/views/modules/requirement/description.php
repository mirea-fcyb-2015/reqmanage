<div class="panel panel-gray">
    <div class="options">
        <ul class="nav nav-tabs">
            <li>
                <a href="<?=site_url('requirement/'. $requirement->id) ?>">К требованию</a>
            </li>
            <li class="active">
                <a href="<?=site_url('requirement/description/'. $requirement->id) ?>">Редактирование</a>
            </li>
        </ul>
    </div>
	<div class="panel-body">
		<?php echo form_open(null, 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название требования</label>
            <div class="col-sm-8">
                <?php echo form_input('title', $requirement->title, 'class="form-control" placeholder="Название требования"'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <?php echo form_textarea(array('name' => 'description'), $requirement->description, 'id="description"'); ?>
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