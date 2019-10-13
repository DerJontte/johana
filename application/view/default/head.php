<head>

	<?php // TODO: STATIC-FOLDER ATT FUNGERA! ?>

	<?php if(!empty($stylesheets)): ?>
	<?php foreach ($stylesheets as $stylesheet): ?>
			<link rel="stylesheet" type="text/css" href="<?php echo $stylesheet ?>">
	<?php endforeach ?>
	<?php endif ?>

	<?php if(!empty($inline_scripts)): ?>
	<?php foreach ($inline_scripts as $inline_script) echo $inline_script ?>
	<?php endif ?>

	<?php if(!empty($scripts)): ?>
	<?php foreach ($scripts as $script) require_once $script ?>
	<?php endif ?>
</head>
