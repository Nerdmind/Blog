<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Template                    [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

switch($FEED['TYPE']) {
	case 'post':
		$title = escapeHTML($BLOGMETA['NAME']).' ['.$Language->template('feed_only_posts').']';
		$self = Application::getURL('feed/post/');
		break;
	case 'page':
		$title = escapeHTML($BLOGMETA['NAME']).' ['.$Language->template('feed_only_pages').']';
		$self = Application::getURL('feed/page/');
		break;
	default:
		$title = escapeHTML($BLOGMETA['NAME']);
		$self = Application::getURL('feed/');
}
?>
<?='<?xml version="1.0" encoding="UTF-8" ?>'?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
		<title><?=$title?></title>
		<link><?=$self?></link>
		<language><?=$BLOGMETA['LANG']?></language>
		<description><?=escapeHTML($BLOGMETA['DESC'])?></description>

		<atom:link href="<?=$self?>" rel="self" type="application/rss+xml" />

		<image>
			<title><?=escapeHTML($BLOGMETA['NAME'])?></title>
			<url><?=Application::getTemplateURL('rsrc/logo.png')?></url>
			<link><?=$self?></link>
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