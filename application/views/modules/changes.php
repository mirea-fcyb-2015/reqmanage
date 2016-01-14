<div class="panel">
	<?php if(isset($project)) { ?>
		<div class="panel-heading">
			Последние изменения в проекте "<?php echo $project->title; ?>"
		</div>
	<?php } ?>
    <div class="panel-body">
    	<?php 
    		if(!empty($changes)) { ?>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
	            <thead>
					<tr>
						<th>Изменение</th>
						<th>Автор изменения</th>
						<th>Дата</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($changes as $c):?>
						<? $user = $this->user->user($c->who)->row(); ?>
						<tr>
				            <td><?=anchor($what[$c->what] .'/'. $c->what_id, $c->description) ?></td>
				            <td><? echo $user->first_name .' '. $user->last_name ?></td>
				            <td><?=date('d.m.Y H:i', $c->when) ?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php } 
			else { ?>
			<div class="alert alert-dismissable alert-warning">
				<strong>Внимание!</strong> В проекте не было никаких изменений.
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			</div>
		<?php } ?>
	</div>
</div>