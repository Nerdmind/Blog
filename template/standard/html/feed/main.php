<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Template                    [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$BLOGMETA_NAME = escapeHTML($BLOGMETA['NAME']);

switch($FEED['TYPE']) {
	case 'post':
		$title = $Language->text('feed_name_posts', $BLOGMETA_NAME);
		$self = Application::getURL('feed/post/');
		break;
	case 'page':
		$title = $Language->text('feed_name_pages', $BLOGMETA_NAME);
		$self = Application::getURL('feed/page/');
		break;
	default:
		$title = $Language->text('feed_name_items', $BLOGMETA_NAME);
		$self = Application::getURL('feed/');
}
?>
<?='<?xml version="1.0" encoding="UTF-8" ?>'?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
		<title><?=$title?></title>
		<link><?=Application::getURL()?></link>
		<language><?=$BLOGMETA['LANG']?></language>
		<description><?=escapeHTML($BLOGMETA['DESC'])?></description>

		<atom:link href="<?=$self?>" rel="self" type="application/rss+xml" />

		<image>
			<title><?=$title?></title>
			<url><?=Application::getTemplateURL('rsrc/logo.png')?></url>
			<link><?=Application::getURL()?></link>
		</image>

		<!-- Feed items of type "post" -->
		<?php foreach($FEED['LIST']['POSTS'] as $item): ?>
			<?php echo $item ?>
		<?php endforeach; ?>

		<!-- Feed items of type "page" -->
		<?php foreach($FEED['LIST']['PAGES'] as $item): ?>
			<?php echo $item ?>
		<?php endforeach; ?>
	</channel>
</rss>