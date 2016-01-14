<div class="row">
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-toyo" href="<?php echo site_url('project/report/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-download"></i></div>
				<div class="pull-right"><span class="badge">Отчет</span></div>
			</div>
			<div class="tiles-footer">
				ГОСТ 19.201
			</div>
		</a>
	</div>
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-orange" href="<?php echo site_url('project/changes/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-pencil"></i></div>
				<div class="pull-right"><span class="badge"><?php echo $changes_count; ?></span></div>
			</div>
			<div class="tiles-footer">
				Правки
			</div>
		</a>
	</div>
	<div class="col-md-2">
		<a class="shortcut-tiles tiles-success" href="<?php echo site_url('project/managers/'. $project->id); ?>">
			<div class="tiles-body">
				<div class="pull-left"><i class="fa fa-users"></i></div>
			</div>
			<div class="tiles-footer">
				Менеджеры проекта
			</div>
		</a>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Редактирование иерархии</h4>
		<div class="options">
			<ul class="nav nav-tabs">
				<li>
					<a href="<?=site_url('project/'. $project->id) ?>">Информация</a>
				</li>
				<li class="active">
					<a href="<?=site_url('project/hierarchy/'. $project->id) ?>">Иерархия</a>
				</li>
				<li>
					<a href="<?=site_url('project/description/'. $project->id) ?>">Редактировать</a>
				</li>
			</ul>
      </div>
	</div>
	<div class="panel-body">
        <div id="order" class="dd"> </div>
		<input type="button" id="save" value="Сохранить" class="btn-primary btn" />
		<script>
		$(function() {
			$.post('<?=site_url('project/order_ajax/'. $project->id); ?>', {}, function(data){
				$('#order').html(data);
			});

			$('.dd').nestable({ /* config options */ });

			$('#save').click(function(){
				// oSortable = $('.sortable').nestedSortable('toArray');

				$('.dd').slideUp(function(){
					$.post('<?=site_url('project/order_ajax/'. $project->id); ?>', {sortable : $('.dd').nestable('serialize') }, function(data){
						$('.dd').html(data);
						$('.dd').slideDown();
					});
				});
				
			});
		});
		</script>
	</div>
</div>