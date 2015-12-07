<div class="panel panel-primary">
	<div class="panel-heading">
		<h4>Редактирование иерархии</h4>
		<div class="options">
			<ul class="nav nav-tabs">
				<li>
					<a href="<?=site_url('project/'. $project->id) ?>">Таблица</a>
				</li>
				<li class="active">
					<a href="<?=site_url('project/hierarchy/'. $project->id) ?>">Иерархия</a>
				</li>
				<li>
					<a href="<?=site_url('project/description/'. $project->id) ?>">Описание</a>
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