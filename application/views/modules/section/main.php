<div class="panel">
        <div class="options">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?php echo site_url('section/'. $section->id) ?>">Описание</a>
                </li>
                <li>
                    <a href="<?php echo site_url('section/description/'. $section->id) ?>">Редактирование описание</a>
                </li>
            </ul>
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
    <div class="panel-heading">
        <h4>Добавление требования</h4>
        <div class="options">
            <a class="panel-collapse" href="#"><i class="fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <?php echo form_open(null, 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="req_title" class="col-sm-3 control-label">Название требования</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="req_title" name="req_title" placeholder="Название требования">
            </div>
        </div>
        <div class="form-group">
            <label for="req_text" class="col-sm-3 control-label">Описание</label>
            <div class="col-sm-6">
                <textarea name="req_text" id="req_text" cols="50" rows="4" class="form-control"></textarea>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                        <?php echo form_submit('button', 'Добавить', 'class="btn-primary btn"'); ?>
                    </div>
                </div>
            </div>
        </div>
        <? echo form_close(); ?>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4>Список требований</h4>
        <div class="options">
            <a class="panel-collapse" href="#"><i class="fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <?php show_requirements_for_nonfunctional($requirements); ?>
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