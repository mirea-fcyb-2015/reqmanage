<?=$this->uri->segment(1) ?>
<div class="panel panel-primary">
    <div class="panel-body">
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
	</div>
</div>