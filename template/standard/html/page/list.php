<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Page List Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-file-text-o"></i><?=$Language->text('page_overview')?><span class="head-link brackets"><i class="fa fa-rss"></i><a href="<?=Application::getURL('feed/page/')?>" title="Nur Seiten">Feed</a></span></h1>
<p><?=$Language->template('page_overview_heading_desc', $PAGINATION['THIS'])?></p>

<ul class="item-list page">
	<?php foreach($LIST['PAGES'] as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</ul>

<?=$PAGINATION['HTML']?>