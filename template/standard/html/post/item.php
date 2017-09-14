<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<article class="item">
	<header>
		<h2>
			<a title="<?=$Language->text('select_post')?>: »<?=escapeHTML($POST['ATTR']['NAME'])?>«" href="<?=$POST['URL']?>"><?=escapeHTML($POST['ATTR']['NAME'])?></a>
		</h2>
		<time class="brackets info" datetime="<?=$POST['ATTR']['TIME_INSERT']?>"><?=parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
	</header>
	<blockquote cite="<?=$POST['URL']?>">
		<?=$POST['BODY']['HTML']()?>
	</blockquote>
</article>