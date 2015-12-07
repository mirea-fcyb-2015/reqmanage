<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

		<?php echo form_open('auth/reset_password/' . $code, 'class="form-horizontal"');?>
			<div class="form-group">
				<label for="new_password" class="col-md-1 control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
	            <div class="col-md-6">
					<?php echo form_input($new_password, NULL,  'class="form-control"');?>
				</div>
			</div>

			<div class="form-group">
				<?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm', 'class="col-md-1 control-label"');?>
	            <div class="col-md-6">
					<?php echo form_input($new_password_confirm, NULL,  'class="form-control"');?>
				</div>
			</div>

			<?php echo form_input($user_id);?>
			<?php echo form_hidden($csrf); ?>

			<div class="form-group">
	            <div class="col-sm-offset-1 col-sm-2">
					<?php echo form_submit('submit', lang('reset_password_submit_btn'), 'class="btn btn-primary"');?>
				</div>
			</div>

		<?php echo form_close();?>
    </div>
</div>