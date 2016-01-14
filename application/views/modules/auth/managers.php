<div class="panel panel-primary">
    <div class="panel-body">
        <div id="infoMessage"><?php echo $message;?></div>

        <p><?php echo lang('index_subheading');?></p>

		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
            <thead>
				<tr>
					<th><?php echo lang('index_fname_th');?></th>
					<th><?php echo lang('index_lname_th');?></th>
					<th><?php echo lang('index_email_th');?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user):?>
					<tr>
			            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>