<div class="panel panel-gray">
    <div class="options">
        <ul class="nav nav-tabs">
            <li>
                <a href="<?=site_url('section/'. $section->id) ?>">К разделу</a>
            </li>
            <li class="active">
                <a href="<?=site_url('section/description/'. $section->id) ?>">Редактирование</a>
            </li>
        </ul>
    </div>
	<div class="panel-body">
		<?php echo form_open(null, 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название раздела</label>
            <div class="col-sm-8">
                <?php 
                	if($section->is_default)
                		echo form_input('title', $section->title, 'class="form-control" placeholder="Название раздела" disabled'); 
                	else
                		echo form_input('title', $section->title, 'class="form-control" placeholder="Название раздела"'); 
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <?php echo form_textarea(array('name' => 'description'), $section->description, 'id="description"'); ?>
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