<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post List Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-newspaper-o"></i><?=$Language->text('post_overview')?><span class="head-link brackets"><i class="fa fa-rss"></i><a href="<?=Application::getURL('feed/post/')?>" title="<?=$Language->text('feed_name_posts', escapeHTML($BLOGMETA['NAME']))?>">Feed</a></span></h1>
<p><?=$Language->text('post_overview_heading_desc', $PAGINATION['THIS'])?></p>

<div class="item-container post">
	<?php foreach($LIST['POSTS'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>