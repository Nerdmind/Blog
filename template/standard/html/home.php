<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Home Template                    [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-home"></i><?=$Language->text('home_heading_text', escapeHTML(Application::get('BLOGMETA.NAME')))?><span class="head-link brackets"><i class="fa fa-rss"></i><a href="<?=Application::getURL('feed/')?>" title="<?=$Language->text('feed_name_items', escapeHTML($BLOGMETA['NAME']))?>">Feed</a></span></h1>
<p><?=$Language->text('home_heading_desc', Application::get('POST.LIST_SIZE'))?></p>

<div class="item-container post">
	<?php foreach($LIST['POSTS'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>