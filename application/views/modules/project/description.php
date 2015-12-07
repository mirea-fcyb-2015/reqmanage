<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Список разделов</h4>
		<div class="options">
			<ul class="nav nav-tabs">
				<li>
					<a href="<?=site_url('project/'. $project->id) ?>">Таблица</a>
				</li>
				<li>
					<a href="<?=site_url('project/hierarchy/'. $project->id) ?>">Иерархия</a>
				</li>
				<li class="active">
					<a href="<?=site_url('project/description/'. $project->id) ?>">Описание</a>
				</li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
		<form>
			<textarea class="js-st-instance"></textarea>
		</form>
        <script>
            new SirTrevor.Editor({ el: $('.js-st-instance') });
        </script>
	</div>
</div>