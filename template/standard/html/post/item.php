<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<li class="item-list-li post">
	<header>
		<h2>
			<a href="<?=$POST['URL']?>"><?=escapeHTML($POST['ATTR']['NAME'])?></a>
			<time class="brackets info" datetime="<?=$POST['ATTR']['TIME_INSERT']?>"><?=parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->template('date_format'))?></time>
		</h2>
	</header>
	<blockquote cite="<?=$POST['URL']?>">
		<?=$POST['BODY']['HTML']()?>
	</blockquote>
</li>