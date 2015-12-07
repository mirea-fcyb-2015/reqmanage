<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Список разделов</h4>
		<div class="options">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="<?=current_url() ?>">Таблица</a>
				</li>
				<li>
					<a href="<?=site_url('project/hierarchy/'. $project->id) ?>">Иерархия</a>
				</li>
				<li>
					<a href="<?=site_url('project/description/'. $project->id) ?>">Описание</a>
				</li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
            <thead>
                <tr>
                    <th></th>
                    <th>Название</th>
                    <th>Количество требований</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <? sections_table($sections); ?>
            </tbody>
        </table>
	</div>
</div>