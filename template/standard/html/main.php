<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Main Template                    [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8" />
	<meta name="referrer" content="origin-when-crossorigin" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

<?php if(isset($HEAD['DESC'])): ?>
	<meta name="description" content="<?=escapeHTML($HEAD['DESC'])?>" />
<?php endif; ?>

<?php if(isset($HEAD['PERM'])): ?>
	<link rel="canonical" href="<?=$HEAD['PERM']?>" />
<?php endif; ?>

	<meta property="og:site_name" content="<?=escapeHTML($BLOGMETA['NAME'])?>" />
	<meta property="og:title" content="<?=escapeHTML($HEAD['NAME'])?>" />
	<meta property="og:image" content="<?=Application::getTemplateURL('rsrc/logo.png')?>" />

<?php if(isset($HEAD['OG_IMAGES'])): ?>
	<?php foreach($HEAD['OG_IMAGES'] as $imageURL): ?>
		<meta property="og:image" content="<?=$imageURL?>" />
	<?php endforeach; ?>
<?php endif; ?>

	<link rel="icon" href="<?=Application::getURL('favicon.ico')?>" />
	<link rel="stylesheet" href="<?=Application::getTemplateURL('rsrc/main.css')?>" />

	<link rel="alternate" type="application/rss+xml" href="<?=Application::getURL('feed/')?>" title="<?=escapeHTML($BLOGMETA['NAME'])?>" />
	<link rel="alternate" type="application/rss+xml" href="<?=Application::getURL('feed/post/')?>" title="<?=escapeHTML($BLOGMETA['NAME'])?> [<?=$Language->template('feed_only_posts')?>]" />
	<link rel="alternate" type="application/rss+xml" href="<?=Application::getURL('feed/page/')?>" title="<?=escapeHTML($BLOGMETA['NAME'])?> [<?=$Language->template('feed_only_pages')?>]" />

	<title><?=escapeHTML("{$HEAD['NAME']} | {$BLOGMETA['NAME']} {$BLOGMETA['DESC']}")?></title>
</head>
<body>
	<section id="container">
		<header id="main-header">
			<section>
				<a href="<?=Application::getURL()?>" title="<?=escapeHTML("{$BLOGMETA['NAME']} {$BLOGMETA['DESC']}")?>">
					<img id="main-logo" src="<?=Application::getTemplateURL('rsrc/logo.png')?>" alt="<?=escapeHTML($BLOGMETA['NAME'])?>" />
				</a>
			</section>
			<nav id="main-navi">
				<label for="toogle-nav" id="toogle-nav-label" class="fa fa-bars"></label>
				<input type="checkbox" id="toogle-nav" />
				<ul>
					<li>
						<a href="<?=Application::getURL()?>" title="<?=$Language->template('navigation_home_desc', escapeHTML($BLOGMETA['NAME']))?>">
							<i class="fa fa-home"></i><?=$Language->template('navigation_home_text')?>
						</a>
					</li>
					<li>
						<a href="<?=Application::getPostURL()?>" title="<?=$Language->text('post_overview')?>">
							<i class="fa fa-newspaper-o"></i><?=$Language->text('posts')?>
						</a>
					</li>
					<li>
						<a href="<?=Application::getPageURL()?>" title="<?=$Language->text('page_overview')?>">
							<i class="fa fa-file-text-o"></i><?=$Language->text('pages')?>
						</a>
					</li>
					<li>
						<a href="<?=Application::getUserURL()?>" title="<?=$Language->text('user_overview')?>">
							<i class="fa fa-user"></i><?=$Language->text('users')?>
						</a>
					</li>
					<li>
						<a href="<?=Application::getURL('search/')?>" title="<?=$Language->template('navigation_search_desc')?>">
							<i class="fa fa-search"></i><?=$Language->template('navigation_search_text')?>
						</a>
					</li>
				</ul>
			</nav>
		</header>
		<main>
			<?=$HTML?>
		</main>
		<footer id="main-footer">
			&copy; <?=escapeHTML($BLOGMETA['NAME'])?>
		</footer>
	</section>
</body>
</html>