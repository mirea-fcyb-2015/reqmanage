<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <div class="col-sm-offset-2">
            <div class="form-group"><?php echo lang('create_user_subheading');?></div>
        </div>

        <?php echo form_open("users/create_user", 'class="form-horizontal"');?>

        <div class="form-group">
            <?php echo lang('create_user_fname_label', 'first_name', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($first_name, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <?php echo lang('create_user_lname_label', 'last_name', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($last_name, NULL, 'class="form-control"');?>
            </div>
        </div>

        <?php
            if($identity_column!=='email') {
                echo '<div class="form-group">';
                echo lang('create_user_identity_label', 'identity', 'class="col-md-2 control-label"');
                echo '<div class="col-md-6">';
                echo form_error('identity');
                echo form_input($identity, NULL, 'class="form-control"');
                echo '</div></div>';
            }
        ?>

        <div class="form-group">
            <?php echo lang('create_user_company_label', 'company', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($company, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <?php echo lang('create_user_email_label', 'email', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($email, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <?php echo lang('create_user_phone_label', 'phone', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($phone, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <?php echo lang('create_user_password_label', 'password', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($password, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <?php echo lang('create_user_password_confirm_label', 'password_confirm', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($password_confirm, NULL, 'class="form-control"');?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-2">
                <?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary"');?>
            </div>
        </div>

        <?php echo form_close();?>
    </div>
</div>