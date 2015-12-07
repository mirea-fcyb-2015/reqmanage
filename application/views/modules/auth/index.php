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
					<th><?php echo lang('index_groups_th');?></th>
					<th><?php echo lang('index_status_th');?></th>
					<th><?php echo lang('index_action_th');?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user):?>
					<tr>
			            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
						<td>
							<?php foreach ($user->groups as $group):?>
								<?php echo anchor("users/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
			                <?php endforeach?>
						</td>
						<td><?php echo ($user->active) ? anchor("users/deactivate/".$user->id, lang('index_active_link')) : anchor("users/activate/". $user->id, lang('index_inactive_link'));?></td>
						<td><?php echo anchor("users/edit_user/".$user->id, 'Редактировать') ;?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>

		<p><?php echo anchor('users/create_user', lang('index_create_user_link'))?> | <?php echo anchor('users/create_group', lang('index_create_group_link'))?></p>
	</div>
</div>