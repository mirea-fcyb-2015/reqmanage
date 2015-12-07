<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <div class="col-sm-offset-1">
            <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>
        </div>

		<?php echo form_open("users/forgot_password", 'class="form-horizontal"');?>
        <div class="form-group">
	      	<label for="email" class="col-md-1 control-label"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label>
            <div class="col-md-6">
		      	<?php echo form_input($email, NULL,  'class="form-control"');?>
		    </div>
		</div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
        		<?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-primary"');?>
        	</div>
		</div>
		<?php echo form_close();?>
	</div>
</div>