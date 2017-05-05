<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<li class="item-list-li user">
	<header>
		<h2>
			<a href="<?=$USER['URL']?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
			<span class="info brackets"><?=escapeHTML($USER['ATTR']['USERNAME'])?></span>
		</h2>
	</header>
	<blockquote cite="<?=$USER['URL']?>">
		<?=$USER['BODY']['HTML']()?>
	</blockquote>
</li>