<h1>
	<?php echo $message ?>
</h1>

<table>
	<thead>
	<tr>
		<th width="200">user.User</th>
		<th>db.User</th>
	</tr>
	</thead>
	<tbody>
	<?php if($users) foreach ($users as $user): ?>
		<tr>
			<td><?php print_r($user['user_User']) ?></td>
			<td><?php print_r($user['db_User']) ?></td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
