<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Page Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<article class="item">
	<header>
		<h2>
			<a title="<?=$Language->text('select_page')?>: »<?=escapeHTML($PAGE['ATTR']['NAME'])?>«" href="<?=$PAGE['URL']?>"><?=escapeHTML($PAGE['ATTR']['NAME'])?></a>
		</h2>
		<time class="brackets info" datetime="<?=$PAGE['ATTR']['TIME_INSERT']?>"><?=parseDatetime($PAGE['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
	</header>
	<blockquote cite="<?=$PAGE['URL']?>">
		<p><?=excerpt($PAGE['BODY']['HTML'](), 600)?></p>
	</blockquote>
</article>