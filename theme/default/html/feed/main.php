<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Feed Template                              [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$title = escapeHTML($BLOGMETA['NAME']);
$self = Application::getURL('feed/');
?>
<?='<?xml version="1.0" encoding="UTF-8" ?>'?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/">
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

		<?php foreach($FEED['LIST']['POSTS'] as $post): ?>
			<?php echo $post ?>
		<?php endforeach ?>
	</channel>
</rss>
