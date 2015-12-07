<div class="panel panel-primary">
    <div class="panel-body">

        <div class="col-sm-offset-1">
			<?php echo sprintf(lang('deactivate_subheading'), $user->username);?>
		</div>

		<?php echo form_open("users/deactivate/".$user->id, 'class="form-horizontal"');?>
		
		<div class="col-sm-offset-1">
	        <div class="radio">
				<label>
					<input type="radio" name="confirm" value="yes" checked="checked" />
					<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
				</label>
			</div>
	        <div class="radio">
				<label>
					<input type="radio" name="confirm" value="no" />
					<?php echo lang('deactivate_confirm_n_label', 'confirm');?>
				</label>
			</div>

			<?php echo form_hidden($csrf); ?>
			<?php echo form_hidden(array('id'=>$user->id)); ?>

	        <div class="form-group">
	        	<br>
	            <div class="col-sm-2">
	        		<?php echo form_submit('submit', lang('deactivate_submit_btn'), 'class="btn btn-primary"');?>
	        	</div>
	        </div>
		</div>

		<?php echo form_close();?>
	</div>
</div>