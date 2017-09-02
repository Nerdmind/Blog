<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Main Template                    [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Escape parameters which are used several times here to reduce escapeHTML calls
#===============================================================================
$HEAD_NAME = isset($HEAD['NAME']) ? escapeHTML($HEAD['NAME']) : NULL;
$HEAD_DESC = isset($HEAD['DESC']) ? escapeHTML($HEAD['DESC']) : NULL;
$BLOGMETA_NAME = escapeHTML($BLOGMETA['NAME']);
$BLOGMETA_DESC = escapeHTML($BLOGMETA['DESC']);
?>
<!DOCTYPE html>
<html lang="<?=$BLOGMETA['LANG']?>">
<head>
	<meta charset="UTF-8" />
	<meta name="referrer" content="origin-when-crossorigin" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

<?php if(isset($HEAD_DESC)): ?>
	<meta name="description" content="<?=$HEAD_DESC?>" />
<?php endif; ?>

<?php if(isset($HEAD['PERM'])): ?>
	<link rel="canonical" href="<?=$HEAD['PERM']?>" />
<?php endif; ?>

	<meta property="og:site_name" content="<?=$BLOGMETA_NAME?>" />
	<meta property="og:title" content="<?=$HEAD_NAME?>" />
	<meta property="og:image" content="<?=Application::getTemplateURL('rsrc/logo.png')?>" />

<?php if(isset($HEAD['OG_IMAGES'])): ?>
	<?php foreach($HEAD['OG_IMAGES'] as $imageURL): ?>
		<meta property="og:image" content="<?=$imageURL?>" />
	<?php endforeach; ?>
<?php endif; ?>

	<link rel="icon" href="<?=Application::getTemplateURL('rsrc/favicon.ico')?>" />
	<link rel="stylesheet" href="<?=Application::getTemplateURL('rsrc/main.css')?>" title="<?=$BLOGMETA_NAME?>" />

	<link rel="alternate" type="application/rss+xml" title="<?=$Language->text('feed_name_items', $BLOGMETA_NAME)?>" href="<?=Application::getURL('feed/')?>" />
	<link rel="alternate" type="application/rss+xml" title="<?=$Language->text('feed_name_posts', $BLOGMETA_NAME)?>" href="<?=Application::getURL('feed/post/')?>" />
	<link rel="alternate" type="application/rss+xml" title="<?=$Language->text('feed_name_pages', $BLOGMETA_NAME)?>" href="<?=Application::getURL('feed/page/')?>" />

	<script defer src="<?=Application::getTemplateURL('rsrc/main.js')?>"></script>

	<title><?="{$HEAD_NAME} | {$BLOGMETA_NAME} {$BLOGMETA_DESC}"?></title>
</head>
<body>
	<div id="container">
		<header id="main-header">
			<div>
				<a href="<?=Application::getURL()?>" title="<?="{$BLOGMETA_NAME} {$BLOGMETA_DESC}"?>">
					<img id="main-logo" src="<?=Application::getTemplateURL('rsrc/logo.png')?>" alt="<?=$BLOGMETA_NAME?>" />
				</a>
			</div>
			<nav id="main-navi">
				<label for="toogle-nav" id="toogle-nav-label" class="fa fa-bars"></label>
				<input type="checkbox" id="toogle-nav" />
				<ul>
					<li>
						<a href="<?=Application::getURL()?>" title="<?=$Language->text('navigation_home_desc', $BLOGMETA_NAME)?>">
							<i class="fa fa-home"></i><?=$Language->text('navigation_home_text')?>
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
						<a href="<?=Application::getURL('search/')?>" title="<?=$Language->text('navigation_search_desc')?>">
							<i class="fa fa-search"></i><?=$Language->text('navigation_search_text')?>
						</a>
					</li>
				</ul>
			</nav>
		</header>
		<main>
			<?=$HTML?>
		</main>
		<footer id="main-footer">
			&copy; <?=$BLOGMETA_NAME?>
		</footer>
	</div>
</body>
</html>