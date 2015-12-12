<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <div class="col-sm-offset-2">
              <p><?php echo lang('edit_user_subheading');?></p>
        </div>

        <?php echo form_open(uri_string(), 'class="form-horizontal"');?>
        <div class="form-group">
            <?php echo lang('edit_user_fname_label', 'first_name', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($first_name, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('edit_user_lname_label', 'last_name', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($last_name, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('edit_user_company_label', 'company', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($company, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('edit_user_phone_label', 'phone', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($phone, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('edit_user_password_label', 'password', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($password, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm', 'class="col-md-2 control-label"');?>
            <div class="col-md-6">
                <?php echo form_input($password_confirm, NULL, 'class="form-control"');?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
                <div class="checkbox">
                    <?php if ($this->user->is_admin()): ?>
                        <h3><?php echo lang('edit_user_groups_heading');?></h3>
                        <?php foreach ($groups as $group):?>
                            <label class="checkbox">
                                <?php
                                $gID=$group['id'];
                                $checked = null;
                                $item = null;
                                foreach($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                        $checked= ' checked="checked"';
                                        break;
                                    }
                                }
                                ?>
                                <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                                <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                            </label>
                        <?php endforeach?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php echo form_hidden('id', $user->id);?>
        <?php echo form_hidden($csrf); ?>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-2">
                <?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-primary"');?>
            </div>
        </div>

        <?php echo form_close();?>
    </div>
</div>