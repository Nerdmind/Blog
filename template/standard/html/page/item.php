<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Page Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<li class="item-list-li page">
	<header>
		<h2>
			<a href="<?=$PAGE['URL']?>"><?=escapeHTML($PAGE['ATTR']['NAME'])?></a>
			<time class="brackets info" datetime="<?=$PAGE['ATTR']['TIME_INSERT']?>"><?=parseDatetime($PAGE['ATTR']['TIME_INSERT'], $Language->template('date_format'))?></time>
		</h2>
	</header>
	<blockquote cite="<?=$PAGE['URL']?>">
		<p><?=excerpt($PAGE['BODY']['HTML'](), 600)?></p>
	</blockquote>
</li>