<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8" />
	<meta name="referrer" content="origin-when-crossorigin" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="<?=Application::getTemplateURL('rsrc/main.css')?>" />
	<script defer src="<?=Application::getTemplateURL('rsrc/main.js')?>"></script>
	<title><?=escapeHTML($NAME)?> | Administration</title>
</head>
<body>
	<header id="main-header">
		<div class="header-line">
			<div class="header-content">
				<a href="<?=Application::getAdminURL()?>"><img id="header-logo" src="<?=Application::getTemplateURL('rsrc/icon-public-domain.svg')?>" alt="Administration" /></a>
				<div id="header-text">Administration</div>
				<div id="header-desc">PHP7 blogging application by <span>Nerdmind</span>!</div>
			</div>
		</div>
		<div class="header-line">
			<div class="header-content">
				<nav id="main-navi">
					<ul>
					<?php if(Application::isAuthenticated()): ?>
						<li><a href="<?=Application::getAdminURL()?>" title="<?=$Language->text('overview_dashboard_text')?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
						<li><a href="<?=Application::getAdminURL('post/')?>" title="<?=$Language->text('post_overview')?>"><i class="fa fa-newspaper-o"></i><span><?=$Language->text('posts')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('page/')?>" title="<?=$Language->text('page_overview')?>"><i class="fa fa-file-text-o"></i><span><?=$Language->text('pages')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('user/')?>" title="<?=$Language->text('user_overview')?>"><i class="fa fa-user"></i><span><?=$Language->text('users')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('database.php')?>" title="<?=$Language->text('overview_database_text')?>"><i class="fa fa-database"></i><span><?=$Language->text('overview_database_text')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('auth.php?action=logout&amp;token='.Application::getSecurityToken())?>"><i class="fa fa-sign-out"></i><span><?=$Language->text('logout')?></span></a></li>
					<?php else: ?>
						<li><a href="<?=Application::getAdminURL('auth.php')?>"><i class="fa fa-sign-in"></i><span><?=$Language->text('login')?></span></a></li>
					<?php endif; ?>
					</ul>
				</nav>
			</div>
		</div>
	</header>
	<div id="main-content">
		<main>
			<?=$HTML?>
		</main>
		<footer id="main-footer">
			<ul>
				<li><i class="fa fa-github-square"></i><a href="https://github.com/Nerdmind/Blog/releases" target="_blank">Releases</a></li>
				<li><i class="fa fa-book"></i><a href="https://github.com/Nerdmind/Blog/wiki" target="_blank">Documentation</a></li>
				<li><i class="fa fa-bug"></i><a href="mailto:Thomas Lange <code@nerdmind.de>">Bugreport</a></li>
			</ul>
		</footer>
	</div>
</body>
</html>