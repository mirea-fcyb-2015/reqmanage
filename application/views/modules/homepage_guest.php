<div class="panel panel-primary">
	<div id="page-heading">
		<h1>Система управления требованиями</h1>
	</div>
    <div class="panel-body">
    	<div class="col-xs-6 col-md-4">
    		
    	</div>
    	<div class="col-xs-6 col-md-4">
	        <?php echo form_open("users/login", 'class="form-horizontal"');?>
	        <div class="form-group">
	        	<label for="identity" class="col-md-2 control-label">Email:</label>
	            <div class="col-md-9">
	                <?php echo form_input('identity', NULL, 'class="form-control"');?>
	            </div>
	        </div>
	        <div class="form-group">
	        	<label for="password" class="col-md-2 control-label">Пароль:</label>
	            <div class="col-md-9">
	                <?php echo form_password('password', NULL, 'class="form-control"');?>
	            </div>
	        </div>
	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-2">
	                <div class="checkbox">
	                    <label>
	                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
	        				запомнить
	                    </label>
	                </div>
	            </div>
	        </div>
	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-2">
	                <?php echo form_submit('submit', 'Вход', 'class="btn btn-primary"');?>
	            </div>
	        </div>
	        <?php echo form_close();?>
    	</div>
    	<div class="col-xs-6 col-md-4">
    		
    	</div>
    </div>
</div>