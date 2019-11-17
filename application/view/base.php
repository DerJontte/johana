<h1>
	<?php echo $message ?>
</h1>

<table>
	<thead>
	<tr>
		<th width="200">user</th>
		<th width="200">host</th>
	</tr>
	</thead>
	<tbody>
	<?php if($users) foreach ($users as $user): ?>
		<tr>
			<td><?php print_r($user['user_user']) ?></td>
			<td><?php print_r($user['user_Host']) ?></td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
