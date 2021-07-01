<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Category Main Template                     [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1 id="category-heading"><i class="fa fa-tag"></i><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></h1>
<?php if($CATEGORIES && array_pop($CATEGORIES)): ?>
	<ul class="category-heading-list">
		<?php foreach($CATEGORIES as $category): ?>
			<li><a href="<?=$category['URL']?>"><?=escapeHTML($category['ATTR']['NAME'])?></a></li>
		<?php endforeach ?>
	</ul>
<?php endif ?>

<div id="category-body">
	<?=$CATEGORY['BODY']['HTML']()?>
</div>

<?php if(!empty($LIST['CATEGORIES'])): ?>
	<div class="item-container category">
		<?php foreach($LIST['CATEGORIES'] as $category): ?>
			<?php echo $category; ?>
		<?php endforeach; ?>
	</div>
<?php endif ?>

<h2 id="category-posts-heading"><i class="fa fa-newspaper-o"></i><?=$Language->text('posts')?></h2>
<div id="category-posts-page"><?=$Language->text('category_posts_page', $PAGINATION['THIS'])?></div>
<?php if($LIST['POSTS']): ?>
	<div class="item-container post">
		<?php foreach($LIST['POSTS'] as $post): ?>
			<?php echo $post; ?>
		<?php endforeach; ?>
	</div>
	<?=$PAGINATION['HTML']?>
<?php else: ?>
	<div class="item-container post">
		<?=$Language->text('category_empty')?>
	</div>
<?php endif ?>
