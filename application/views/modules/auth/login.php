<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <div class="col-sm-offset-1">
            <p><?php echo lang('login_subheading');?></p>
        </div>
        <?php echo form_open("users/login", 'class="form-horizontal"');?>
        <div class="form-group">
            <?php echo lang('login_identity_label', 'identity', 'class="col-md-1 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($identity, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('login_password_label', 'password', 'class="col-md-1 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($password, NULL,  'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                        <?php echo lang('login_remember_label', 'remember');?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-4">
                <?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-primary"');?>
                <?php echo anchor('users/register', 'Регистрация', 'class="btn btn-default"'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
                <a href="forgot_password"><?php echo lang('login_forgot_password');?></a>
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</div>