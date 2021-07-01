<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Category Item Template                     [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<article class="item">
	<header>
		<h2 class="fa fa-tag">
			<a title="<?=$Language->text('select_category')?>: »<?=escapeHTML($CATEGORY['ATTR']['NAME'])?>«" href="<?=$CATEGORY['URL']?>">
				<?=escapeHTML($CATEGORY['ATTR']['NAME'])?>
			</a>
		</h2>
		<span class="brackets info">
			<?=$Language->text('posts')?>: <?=$COUNT['POST']?> –
			<?=$Language->text('categories')?>: <?=$COUNT['CHILDREN']?>
		</span>
	</header>
	<?php if($IS_ROOT): ?>
	<blockquote cite="<?=$CATEGORY['URL']?>">
		<?=$CATEGORY['BODY']['HTML']()?>
	</blockquote>
	<?php endif ?>
</article>
