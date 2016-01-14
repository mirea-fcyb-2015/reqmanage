<div class="panel panel-primary">
	<div class="panel-heading"><h4>Описание</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?=site_url('requirement/'. $requirement->id) ?>">Список атрибутов</a>
                </li>
                <li class="active">
                    <a href="<?=site_url('requirement/description/'. $requirement->id) ?>">Редактировать</a>
                </li>
            </ul>
        </div>
    </div>
	<div class="panel-body">
		<?php 
			echo form_open();
			echo form_textarea(array('name' => 'description'), $requirement->description, 'id="description"');
			echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"');
			echo form_close();
		?>
	</div>
</div>