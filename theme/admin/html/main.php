<?php
if($toogle = HTTP::GET('colors')) {
	$options = ['path' => '/', 'samesite' => 'Lax'];

	if($toogle === 'dark') {
		$_COOKIE['dark_mode'] = TRUE;
		setcookie('dark_mode', TRUE, $options);
	} else {
		unset($_COOKIE['dark_mode']);
		setcookie('dark_mode', NULL, array_merge($options, ['expires' => -1]));
	}
}

$theme = isset($_COOKIE['dark_mode']) ? 'dark' : 'bright';
?>
<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8">
	<meta name="referrer" content="origin-when-crossorigin">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?=Application::getTemplateURL("rsrc/css/$theme.css")?>">
	<script defer src="<?=Application::getTemplateURL('rsrc/main.js')?>"></script>
	<title><?=escapeHTML($NAME)?> | Administration</title>
</head>
<body>
	<header id="main-header">
		<div class="header-line background">
			<div class="header-content">
				<a href="<?=Application::getURL()?>">
					<img id="header-logo" src="<?=Application::getTemplateURL('rsrc/icon-public-domain.svg')?>" alt="Administration">
				</a>
				<div id="header-text"><?=escapeHTML($BLOGMETA['NAME'])?></div>
				<div id="header-desc"><?=escapeHTML($BLOGMETA['DESC'])?></div>
			</div>
		</div>
		<div class="header-line">
			<div class="header-content">
				<nav id="main-navi">
					<ul>
					<?php if(Application::isAuthenticated()): ?>
						<li><a href="<?=Application::getAdminURL()?>" title="<?=$Language->text('overview_dashboard_text')?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
						<li><a href="<?=Application::getAdminURL('post/')?>" title="<?=$Language->text('post_overview')?>"><i class="fa fa-newspaper-o"></i><span><?=$Language->text('posts')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('category/')?>" title="<?=$Language->text('category_overview')?>"><i class="fa fa-tags"></i><span><?=$Language->text('categories')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('page/')?>" title="<?=$Language->text('page_overview')?>"><i class="fa fa-folder-open"></i><span><?=$Language->text('pages')?></span></a></li>
						<li><a href="<?=Application::getAdminURL('user/')?>" title="<?=$Language->text('user_overview')?>"><i class="fa fa-users"></i><span><?=$Language->text('users')?></span></a></li>
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

	<?=$HTML?>

	<footer id="main-footer">
		<ul>
			<li><i class="fa fa-github-square"></i><a href="https://github.com/Nerdmind/Blog/releases" target="_blank">Releases</a></li>
			<li><i class="fa fa-book"></i><a href="https://github.com/Nerdmind/Blog/wiki" target="_blank">Documentation</a></li>
			<li><i class="fa fa-bug"></i><a href="https://github.com/Nerdmind/Blog/issues">Bugreport</a></li>
			<li>
				<span id="theme-toogle-dark">
					<i class="fa fa-moon"></i><a href="?<?=http_build_query(array_merge($_GET, ['colors' => 'dark']))?>"><?=$Language->text('dark_colors')?></a>
				</span>
				<span id="theme-toogle-bright">
					<i class="fa fa-sun"></i><a href="?<?=http_build_query(array_merge($_GET, ['colors' => 'bright']))?>"><?=$Language->text('bright_colors')?></a>
				</span>
			</li>
		</ul>
	</footer>
</body>
</html>
