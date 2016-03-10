<div class="panel panel-primary">
	<div class="panel-heading">Загрузить проект</div>
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
			<?php echo form_submit('submit', 'Загрузить', 'class="btn btn-primary"'); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>