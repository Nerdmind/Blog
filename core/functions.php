<?php
#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageNaviTemplate($currentSite): Template\Template {
	$Database = Application::getDatabase();
	$Statement = $Database->query(sprintf('SELECT COUNT(id) FROM %s', Page\Attribute::TABLE));

	$lastSite = ceil($Statement->fetchColumn() / Application::get('PAGE.LIST_SIZE'));

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getPageURL('?site=%d'));

	return $PaginationTemplate;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostNaviTemplate($currentSite): Template\Template {
	$Database = Application::getDatabase();
	$Statement = $Database->query(sprintf('SELECT COUNT(id) FROM %s', Post\Attribute::TABLE));

	$lastSite = ceil($Statement->fetchColumn() / Application::get('POST.LIST_SIZE'));

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getPostURL('?site=%d'));

	return $PaginationTemplate;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserNaviTemplate($currentSite): Template\Template {
	$Database = Application::getDatabase();
	$Statement = $Database->query(sprintf('SELECT COUNT(id) FROM %s', User\Attribute::TABLE));

	$lastSite = ceil($Statement->fetchColumn() / Application::get('USER.LIST_SIZE'));

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getUserURL('?site=%d'));

	return $PaginationTemplate;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageItemTemplate(Page\Item $Page, User\Item $User): Template\Template {
	$Template = Template\Factory::build('page/item');
	$Template->set('PAGE', generatePageItemData($Page));
	$Template->set('USER', generateUserItemData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostItemTemplate(Post\Item $Post, User\Item $User): Template\Template {
	$Template = Template\Factory::build('post/item');
	$Template->set('POST', generatePostItemData($Post));
	$Template->set('USER', generateUserItemData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserItemTemplate(User\Item $User): Template\Template {
	$Template = Template\Factory::build('user/item');
	$Template->set('USER', generateUserItemData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateItemData(Item $Item): array {
	return [
		'ID'  => $Item->getID(),
		'URL' => $Item->getURL(),
		'GUID' => $Item->getGUID(),
		'ARGV' => $Item->getArguments(),

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => $Item->getFiles()
		],

		'BODY' => [
			'TEXT' => $Item->getBody(),
			'HTML' => $Item->getHTML()
		],

		'ATTR' => [
			'USER' => $Item->attr('user'),
			'SLUG' => $Item->attr('slug'),
			'NAME' => $Item->attr('name'),
			'BODY' => $Item->attr('body'),
			'ARGV' => $Item->attr('argv'),
			'TIME_INSERT' => $Item->attr('time_insert'),
			'TIME_UPDATE' => $Item->attr('time_update')
		]
	];
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageItemData(Page\Item $Page): array {
	return generateItemData($Page);
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostItemData(Post\Item $Post): array {
	return generateItemData($Post);
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserItemData(User\Item $User): array {
	return [
		'ID'  => $User->getID(),
		'URL' => $User->getURL(),
		'GUID' => $User->getGUID(),
		'ARGV' => $User->getArguments(),

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => $User->getFiles()
		],

		'BODY' => [
			'TEXT' => $User->getBody(),
			'HTML' => $User->getHTML()
		],

		'ATTR' => [
			'SLUG' => $User->attr('slug'),
			'BODY' => $User->attr('body'),
			'ARGV' => $User->attr('argv'),
			'USERNAME' => $User->attr('username'),
			'FULLNAME' => $User->attr('fullname'),
			'MAILADDR' => $User->attr('mailaddr'),
			'TIME_INSERT' => $User->attr('time_insert'),
			'TIME_UPDATE' => $User->attr('time_update')
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
# Return cutted string
#===============================================================================
function cut($string, $length, $replace = ' […]') {
	if(mb_strlen($string) > $length) {
		return preg_replace("/^(.{1,{$length}}\\b).*/su", "\\1{$replace}", $string);
	}

	return $string;
}

#===============================================================================
# Return excerpt content
#===============================================================================
function excerpt($string, $length = 500, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeDoubleLineBreaks($string);
	$string = cut($string, $length, $replace);
	$string = trim($string);
	$string = nl2br($string);

	return $string;
}

#===============================================================================
# Return content for meta description
#===============================================================================
function description($string, $length = 200, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeWhitespace($string);
	$string = cut($string, $length, $replace);

	return $string;
}

#===============================================================================
# Generate a valid slug URL part from a string
#===============================================================================
function makeSlugURL($string, $separator = '-') {
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
function PAGE($id) {
	try {
		$Page = Page\Factory::build($id);
		return generatePageItemData($Page);
	} catch(Page\Exception $Exception) {
		return NULL;
	}
}

#===============================================================================
# Function to get data from specific post in templates
#===============================================================================
function POST($id) {
	try {
		$Post = Post\Factory::build($id);
		return generatePostItemData($Post);
	} catch(Post\Exception $Exception) {
		return NULL;
	}
}

#===============================================================================
# Function to get data from specific user in templates
#===============================================================================
function USER($id) {
	try {
		$User = User\Factory::build($id);
		return generateUserItemData($User);
	} catch(User\Exception $Exception) {
		return NULL;
	}
}
?>