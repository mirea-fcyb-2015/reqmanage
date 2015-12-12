<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Список разделов</h4>
		<div class="options">
			<ul class="nav nav-tabs">
                <li>
                    <a href="<?=site_url('section/'. $section->id) ?>">Список требований</a>
                </li>
                <li class="active">
                    <a href="<?=site_url('section/description/'. $section->id) ?>">Редактировать</a>
                </li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
		<?php 
			echo form_open();
			echo form_textarea(array('name' => 'description'), $section->description, 'id="description"');
			echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"');
			echo form_close();
		?>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Прикрепить файлы</div>
	<div class="panel-body">
		<?php if(isset($message)) { ?>
	        <div class="alert alert-info">
	            <?=$message ?>
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	        </div>
	    <? } ?>
		<?php echo form_open_multipart(); ?>
		<div class="col-md-4">
			<?php echo form_upload(array('name' => 'file')); ?>
		</div>
		<div class="col-md-4">
			<?php echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"'); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>