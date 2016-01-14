<div class="panel panel-primary">
	<div class="panel-heading"><h4>Содержимое раздела</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?php echo site_url('section/'. $section->id) ?>">Описание</a>
                </li>
                <li>
                    <a href="<?php echo site_url('section/description/'. $section->id) ?>">Редактировать</a>
                </li>
            </ul>
        </div>
    </div>
	<div class="panel-body">
            <?php if($section->description) { ?> 
                <?=$section->description ?>
            <?php } 
                else { ?>
            <div class="alert alert-info">
                Нет описания. <a href="<?php echo site_url('section/description/'. $section->id) ?>">Добавьте его. </a>
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
            file_table($files, $file_dir); 
        ?>
    </div>
</div>