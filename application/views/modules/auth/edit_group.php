<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <div class="col-sm-offset-1">
			<p><?php echo lang('edit_group_subheading');?></p>
		</div>

		<?php echo form_open(current_url(), 'class="form-horizontal"');?>
        <div class="form-group">
			<?php echo lang('edit_group_name_label', 'group_name', 'class="col-md-1 control-label"');?>
            <div class="col-md-6">
				<?php echo form_input($group_name, NULL,  'class="form-control"');?>
			</div>
	    </div>

        <div class="form-group">
			<?php echo lang('edit_group_desc_label', 'description', 'class="col-md-1 control-label"');?>
            <div class="col-md-6">
				<?php echo form_input($group_description, NULL,  'class="form-control"');?>
			</div>
		</div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
        		<?php echo form_submit('submit', lang('edit_group_submit_btn'), 'class="btn btn-primary"');?>
        	</div>
        </div>

		<?php echo form_close();?>
	</div>
</div>