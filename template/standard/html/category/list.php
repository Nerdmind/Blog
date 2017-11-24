<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Category List Template           [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-newspaper-o"></i><?=$Language->text('category_overview')?><span class="head-link brackets"><i class="fa fa-rss"></i><a href="<?=Application::getURL('feed/category/')?>" title="<?=$Language->text('feed_name_categories', escapeHTML($BLOGMETA['NAME']))?>">Feed</a></span></h1>
<p><?=$Language->text('category_overview_heading_desc', $PAGINATION['THIS'])?></p>

<div class="item-container category">
	<?php foreach($LIST['CATEGORIES'] as $category): ?>
		<?php echo $category; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>