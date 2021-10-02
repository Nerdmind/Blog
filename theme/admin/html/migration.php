<?php $theme = isset($_COOKIE['dark_mode']) ? 'dark' : 'bright'; ?>
<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8" />
	<meta name="referrer" content="origin-when-crossorigin" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="<?=Application::getTemplateURL("rsrc/css/$theme.css")?>" />
	<title><?=$Language->text('maintenance_mode')?></title>
</head>
<body>
<header id="main-header">
	<div class="header-line background">
		<div class="header-content">
			<img id="header-logo" src="<?=Application::getTemplateURL('rsrc/icon-public-domain.svg')?>" alt="Administration" />
			<div id="header-text"><?=escapeHTML($BLOGMETA['NAME'])?></div>
			<div id="header-desc"><?=$Language->text('maintenance_mode')?></div>
		</div>
	</div>
</header>
<?php
$migrations_list = $MIGRATION['LIST'];
$database_schema = $MIGRATION['SCHEMA_VERSION']['DATABASE'];
$codebase_schema = $MIGRATION['SCHEMA_VERSION']['CODEBASE'];
?>
<main id="main-content">
	<h1><?=$Language->text('maintenance_mode')?></h1>
	<?php if($migrated = $MIGRATION['SUCCESSFUL']): ?>
		<p><?=$Language->text('migration_successful')?></p>
		<ul>
		<?php foreach($migrated as $migration): ?>
			<li>Migration <code><?=$migration?></code></li>
		<?php endforeach ?>
		</ul>
	<?php else: ?>
		<p><?=$Language->text("migration_upgrade", [$database_schema, $codebase_schema])?></p>
		<form action="" method="post">
			<ul class="no-visual-list">
			<?php foreach($migrations_list as $migration => $commands): ?>
				<li>
					<h2><strong>Migration <code><?=$migration?></code></strong></h2>
					<p><?=$Language->text('migration_notice', [$migration-1, $migration])?></p>
					<pre><?=$commands?></pre>
				</li>
			<?php endforeach ?>
			</ul>
			<input type="hidden" name="token" value="<?=Application::getSecurityToken()?>" ?>
			<input type="submit" name="run" value="<?=$Language->text('migration_submit')?>" id="delete-button" />
		</form>
	<?php endif ?>
</main>
<footer id="main-footer">
	<ul>
		<li><i class="fa fa-github-square"></i><a href="https://github.com/Nerdmind/Blog/releases" target="_blank">Releases</a></li>
		<li><i class="fa fa-book"></i><a href="https://github.com/Nerdmind/Blog/wiki" target="_blank">Documentation</a></li>
		<li><i class="fa fa-bug"></i><a href="https://github.com/Nerdmind/Blog/issues">Bugreport</a></li>
	</ul>
</footer>
</body>
</html>
