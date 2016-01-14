<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Содержимое раздела</h4>
		<div class="options">
			<ul class="nav nav-tabs">
                <li>
                    <a href="<?=site_url('section/'. $section->id) ?>">Описание</a>
                </li>
                <li class="active">
                    <a href="<?=site_url('section/description/'. $section->id) ?>">Редактировать</a>
                </li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
		<?php 
			echo form_open();
			echo form_textarea(array('name' => 'description'), $section->description, 'id="description"');
			echo form_submit('submit', 'Сохранить', 'class="btn btn-primary"');
			echo form_close();
		?>
	</div>
</div>