<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8" />
	<meta name="referrer" content="origin-when-crossorigin" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="<?=Application::getTemplateURL('rsrc/main.css')?>" />
	<script src="<?=Application::getTemplateURL('rsrc/main.js')?>"></script>
	<title><?=escapeHTML($NAME)?> | Administration</title>
</head>
<body>
	<header id="main-header">
		<section class="header-line">
			<div class="header-content">
				<a href="<?=Application::getAdminURL()?>"><img id="header-logo" src="<?=Application::getTemplateURL('rsrc/icon-public-domain.svg')?>" alt="Administration" /></a>
				<div id="header-text">Administration</div>
				<div id="header-desc">PHP7 blogging application by <span>Nerdmind</span>!</div>
			</div>
		</section>
		<section class="header-line">
			<div class="header-content">
				<nav id="main-navi">
					<?php if(Application::isAuthenticated()): ?>
						<ul>
							<li><a href="<?=Application::getAdminURL()?>" title="<?=$Language->template('overview_dashboard_text')?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
							<li><a href="<?=Application::getAdminURL('post/')?>" title="<?=$Language->text('post_overview')?>"><i class="fa fa-newspaper-o"></i><span><?=$Language->text('posts')?></span></a></li>
							<li><a href="<?=Application::getAdminURL('page/')?>" title="<?=$Language->text('page_overview')?>"><i class="fa fa-file-text-o"></i><span><?=$Language->text('pages')?></span></a></li>
							<li><a href="<?=Application::getAdminURL('user/')?>" title="<?=$Language->text('user_overview')?>"><i class="fa fa-user"></i><span><?=$Language->text('users')?></span></a></li>
							<li><a href="<?=Application::getAdminURL('database.php')?>" title="<?=$Language->template('overview_database_text')?>"><i class="fa fa-database"></i><span><?=$Language->template('overview_database_text')?></span></a></li>
						</ul>
						<ul>
							<li><a href="<?=Application::getAdminURL('auth.php?action=logout&amp;token='.Application::getSecurityToken())?>"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
						</ul>
					<?php else: ?>
						<ul>
							<li><a href="<?=Application::getAdminURL('auth.php')?>"><i class="fa fa-sign-in"></i><span>Login</span></a></li>
						</ul>
					<?php endif; ?>
				</nav>
			</div>
		</section>
	</header>
	<section id="main-content">
		<main>
			<?=$HTML?>
		</main>
		<footer id="main-footer">
			<i class="fa fa-bug"></i> Report bugs via email to <a href="mailto:Thomas Lange <code@nerdmind.de>">code@nerdmind.de</a>!
		</footer>
	</section>
</body>
</html>