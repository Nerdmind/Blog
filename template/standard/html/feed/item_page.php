<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Item Template [page]        [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<item>
	<title><?=escapeHTML($PAGE['ATTR']['NAME'])?></title>
	<link><?=$PAGE['URL']?></link>
	<guid isPermaLink="false"><?=$PAGE['GUID']?></guid>
	<pubDate><?=parseDatetime($PAGE['ATTR']['TIME_INSERT'], '[RFC2822]')?></pubDate>
	<dc:creator><?=escapeHTML($USER['ATTR']['FULLNAME'])?></dc:creator>
	<description><?=escapeHTML(description($PAGE['BODY']['HTML'], 400))?></description>
	<content:encoded>
		<![CDATA[
			<?=$PAGE['BODY']['HTML']?>
		]]>
	</content:encoded>
	<?php foreach($PAGE['FILE']['LIST'] as $fileURL): ?>
		<media:content url="<?=$fileURL?>" medium="image"></media:content>
	<?php endforeach; ?>
</item>