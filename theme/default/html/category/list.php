<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Category List Template                     [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-tags"></i><?=$Language->text('category_overview')?></h1>
<p><?=$Language->text('category_list_title', $PAGINATION['THIS'])?></p>

<?php if($LIST['CATEGORIES']): ?>
	<div class="item-container category">
		<?php foreach($LIST['CATEGORIES'] as $category): ?>
			<?php echo $category; ?>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p><?=$Language->text('category_list_empty')?></p>
<?php endif ?>

<?=$PAGINATION['HTML']?>
