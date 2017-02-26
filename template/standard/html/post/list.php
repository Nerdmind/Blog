<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post List Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-newspaper-o"></i><?=$Language->text('post_overview')?><span class="head-link brackets"><i class="fa fa-rss"></i><a href="<?=Application::getURL('feed/post/')?>" title="Nur Beiträge">Feed</a></span></h1>
<p><?=$Language->template('post_overview_heading_desc', $PAGINATION['THIS'])?></p>

<ul class="item-list post">
	<?php foreach($LIST['POSTS'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</ul>

<?=$PAGINATION['HTML']?>