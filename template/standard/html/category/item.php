<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Category Item Template           [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<article class="item">
	<header>
		<h2>
			<a title="<?=$Language->text('select_category')?>: »<?=escapeHTML($CATEGORY['ATTR']['NAME'])?>«" href="<?=$CATEGORY['URL']?>"><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></a>
		</h2>
		<time class="brackets info" datetime="<?=$CATEGORY['ATTR']['TIME_INSERT']?>"><?=parseDatetime($CATEGORY['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
	</header>
	<blockquote cite="<?=$CATEGORY['URL']?>">
		<?=$CATEGORY['BODY']['HTML']()?>
	</blockquote>
</article>