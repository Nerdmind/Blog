<?php
#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateNaviTemplate(int $current, $location, $namespace): Template\Template {
	$Database = Application::getDatabase();
	$Attribute = "{$namespace}\\Attribute";

	$Statement = $Database->query(sprintf('SELECT COUNT(id) FROM %s', $Attribute::TABLE));

	$lastSite = ceil($Statement->fetchColumn() / Application::get(strtoupper($namespace).'.LIST_SIZE'));

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $current);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', "{$location}?site=%d");

	return $PaginationTemplate;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getPageURL(), 'Page');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getPostURL(), 'Post');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getUserURL(), 'User');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageItemTemplate(Page\Item $Page, User\Item $User): Template\Template {
	$Template = Template\Factory::build('page/item');
	$Template->set('PAGE', generateItemTemplateData($Page));
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostItemTemplate(Post\Item $Post, User\Item $User): Template\Template {
	$Template = Template\Factory::build('post/item');
	$Template->set('POST', generateItemTemplateData($Post));
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserItemTemplate(User\Item $User): Template\Template {
	$Template = Template\Factory::build('user/item');
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateItemTemplateData(Item $Item): array {
	$ATTR = $Item->getAttribute()->getAll(['password']);
	$ATTR = array_change_key_case($ATTR, CASE_UPPER);

	return [
		'URL' => $Item->getURL(),
		'GUID' => $Item->getGUID(),
		'ARGV' => $Item->getArguments(),

		'ATTR' => $ATTR,

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => $Item->getFiles()
		],

		'BODY' => [
			'TEXT' => function() use($Item) {
				return $Item->getBody();
			},
			'HTML' => function() use($Item) {
				return $Item->getHTML();
			}
		]
	];
}

#===============================================================================
# Parser for datetime formatted strings [YYYY-MM-DD HH:II:SS]
#===============================================================================
function parseDatetime($datetime, $format): string {
	$Language = Application::getLanguage();

	list($date, $time) = explode(' ', $datetime);

	list($DATE['Y'], $DATE['M'], $DATE['D']) = explode('-', $date);
	list($TIME['H'], $TIME['M'], $TIME['S']) = explode(':', $time);

	$M_LIST = [
		'01' => $Language->text('month_01'),
		'02' => $Language->text('month_02'),
		'03' => $Language->text('month_03'),
		'04' => $Language->text('month_04'),
		'05' => $Language->text('month_05'),
		'06' => $Language->text('month_06'),
		'07' => $Language->text('month_07'),
		'08' => $Language->text('month_08'),
		'09' => $Language->text('month_09'),
		'10' => $Language->text('month_10'),
		'11' => $Language->text('month_11'),
		'12' => $Language->text('month_12'),
	];

	$D_LIST = [
		0 => $Language->text('day_6'),
		1 => $Language->text('day_0'),
		2 => $Language->text('day_1'),
		3 => $Language->text('day_2'),
		4 => $Language->text('day_3'),
		5 => $Language->text('day_4'),
		6 => $Language->text('day_5'),
	];

	return strtr($format, [
		'[Y]' => $DATE['Y'],
		'[M]' => $DATE['M'],
		'[D]' => $DATE['D'],
		'[H]' => $TIME['H'],
		'[I]' => $TIME['M'],
		'[S]' => $TIME['S'],
		'[W]' => $D_LIST[date('w', strtotime($datetime))],
		'[F]' => $M_LIST[date('m', strtotime($datetime))],
		'[DATE]' => $date,
		'[TIME]' => $time,
		'[RFC2822]' => date('r', strtotime($datetime))
	]);
}

#===============================================================================
# Get emoticons with unicode characters and description
#===============================================================================
function getEmoticons(): array {
	$Language = Application::getLanguage();

	return [
		':)' => ['&#x1F60A;', $Language->text('emoticon_1F60A')],
		':(' => ['&#x1F61E;', $Language->text('emoticon_1F61E')],
		':D' => ['&#x1F603;', $Language->text('emoticon_1F603')],
		':P' => ['&#x1F61B;', $Language->text('emoticon_1F61B')],
		':O' => ['&#x1F632;', $Language->text('emoticon_1F632')],
		';)' => ['&#x1F609;', $Language->text('emoticon_1F609')],
		';(' => ['&#x1F622;', $Language->text('emoticon_1F622')],
		':|' => ['&#x1F610;', $Language->text('emoticon_1F610')],
		':X' => ['&#x1F635;', $Language->text('emoticon_1F635')],
		':/' => ['&#x1F612;', $Language->text('emoticon_1F612')],
		'8)' => ['&#x1F60E;', $Language->text('emoticon_1F60E')],
		':S' => ['&#x1F61F;', $Language->text('emoticon_1F61F')],
		'xD' => ['&#x1F602;', $Language->text('emoticon_1F602')],
		'^^' => ['&#x1F604;', $Language->text('emoticon_1F604')],
	];
}

#===============================================================================
# Parse emoticons to HTML encoded unicode characters
#===============================================================================
function parseEmoticons($string): string {
	foreach(getEmoticons() as $emoticon => $data) {
		$pattern = '#(^|\s)'.preg_quote($emoticon).'#';
		$replace = " <span title=\"{$data[1]}\">{$data[0]}</span>";

		$string = preg_replace($pattern, $replace, $string);
	}

	return $string;
}

#===============================================================================
# Wrapper function for htmlspecialchars()
#===============================================================================
function escapeHTML($string): string {
	return htmlspecialchars($string);
}

#===============================================================================
# Wrapper function for strip_tags()
#===============================================================================
function removeHTML($string): string {
	return strip_tags($string);
}

#===============================================================================
# Remove all double line breaks from string
#===============================================================================
function removeDoubleLineBreaks($string): string {
	return preg_replace('#(\r?\n){2,}#', "\n\n", $string);
}

#===============================================================================
# Remove all multiple whitespace characters
#===============================================================================
function removeWhitespace($string): string {
	return preg_replace('/\s+/S', ' ', trim($string));
}

#===============================================================================
# Return pseudo-random (hex converted) string
#===============================================================================
function getRandomValue($length = 40): string {
	return strtoupper(bin2hex(random_bytes(ceil($length / 2))));
}

#===============================================================================
# Return truncated string
#===============================================================================
function truncate($string, $length, $replace = '') {
	if(mb_strlen($string) > $length) {
		$truncated = preg_replace("/^(.{1,{$length}}\\b).*/su", '$1', $string);
		$truncated = trim($truncated);

		return "{$truncated}{$replace}";
	}

	return $string;
}

#===============================================================================
# Return excerpt content
#===============================================================================
function excerpt($string, $length = 500, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeDoubleLineBreaks($string);
	$string = truncate($string, $length, $replace);
	$string = nl2br($string);

	return $string;
}

#===============================================================================
# Return content for meta description
#===============================================================================
function description($string, $length = 200, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeWhitespace($string);
	$string = truncate($string, $length, $replace);

	return $string;
}

#===============================================================================
# Generate a valid slug URL part from a string
#===============================================================================
function generateSlug($string, $separator = '-') {
	$string = strtr(mb_strtolower($string), [
		'ä' => 'ae',
		'ö' => 'oe',
		'ü' => 'ue',
		'ß' => 'ss'
	]);

	$string = preg_replace('#[^[:lower:][:digit:]]+#', $separator, $string);

	return trim($string, $separator);
}

#===============================================================================
# Function to get data from specific page in templates
#===============================================================================
function PAGE(int $id): array {
	try {
		$Page = Page\Factory::build($id);
		return generateItemTemplateData($Page);
	} catch(Page\Exception $Exception) {
		return [];
	}
}

#===============================================================================
# Function to get data from specific post in templates
#===============================================================================
function POST(int $id): array {
	try {
		$Post = Post\Factory::build($id);
		return generateItemTemplateData($Post);
	} catch(Post\Exception $Exception) {
		return [];
	}
}

#===============================================================================
# Function to get data from specific user in templates
#===============================================================================
function USER(int $id): array {
	try {
		$User = User\Factory::build($id);
		return generateItemTemplateData($User);
	} catch(User\Exception $Exception) {
		return [];
	}
}
?>