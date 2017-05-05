<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Item Template [page]        [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$HTML = $PAGE['BODY']['HTML']();
?>
<item>
	<title><?=escapeHTML($PAGE['ATTR']['NAME'])?></title>
	<link><?=$PAGE['URL']?></link>
	<guid isPermaLink="false"><?=$PAGE['GUID']?></guid>
	<pubDate><?=parseDatetime($PAGE['ATTR']['TIME_INSERT'], '[RFC2822]')?></pubDate>
	<dc:creator><?=escapeHTML($USER['ATTR']['FULLNAME'])?></dc:creator>
	<description><?=escapeHTML(description($HTML, 400))?></description>
	<content:encoded>
		<![CDATA[
			<?=$HTML?>
		]]>
	</content:encoded>
	<?php foreach($PAGE['FILE']['LIST'] as $fileURL): ?>
		<media:content url="<?=$fileURL?>" medium="image"></media:content>
	<?php endforeach; ?>
</item>