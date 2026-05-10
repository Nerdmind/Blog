<?php
use ORM\Entity\EntityInterface;
use ORM\Entity\Category;
use ORM\Entity\Page;
use ORM\Entity\Post;
use ORM\Entity\User;

use Template\Template as Template;
use Template\Factory as TemplateFactory;

use Parser\ArgumentParser;
use Parser\FunctionParser;
use Parser\EmoticonParser;
use Parser\MarkdownParser;

#===============================================================================
# Create generic pagination template
#===============================================================================
function createPaginationTemplate($current, $last, string $location): Template {
	$params = http_build_query(array_merge($_GET, ['site' => '__SITE__']));
	$params = str_replace('%', '%%', $params);
	$params = str_replace('__SITE__', '%d', $params);

	$pagination = TemplateFactory::build('pagination');
	$pagination->set('THIS', $current);
	$pagination->set('LAST', $last);
	$pagination->set('HREF', "{$location}?{$params}");

	return $pagination;
}

function createEntityItemTemplate(EntityInterface $entity, array $params = []): Template {
	$entityNs = explode('\\', get_class($entity));
	$entityNs = end($entityNs);
	$entityStr = strtolower($entityNs);

	$template = TemplateFactory::build(sprintf('%s/item', $entityStr));

	if(in_array($entityNs, ['Category', 'Post'])) {
		$categoryRepository = Application::getRepository('Category');
	}

	if($entityNs === 'Category') {
		foreach($categoryRepository->findWithParents($entity->getID()) as $category) {
			$categoryData = generateItemTemplateData($category);
			$categoryList[] = $categoryData;
		}

		$template->set('IS_ROOT', $params['is_root'] ?? false);
		$template->set('CATEGORY', $categoryData ?? []);
		$template->set('CATEGORIES', $categories ?? []);
		$template->set('COUNT', [
			'POST' => $categoryRepository->getNumberOfPosts($entity),
			'CHILDREN' => $categoryRepository->getNumberOfChildren($entity)
		]);
	}

	else if($entityNs === 'Post') {
		foreach($categoryRepository->findWithParents($entity->get('category')) as $category) {
			$categoryData = generateItemTemplateData($category);
			$categories[] = $categoryData;
		}

		$template->set('CATEGORY', $categoryData ?? []);
		$template->set('CATEGORIES', $categories ?? []);
	}

	// For pages and posts we have a user relation
	if(in_array($entityNs, ['Page', 'Post'])) {
		$userRepository = Application::getRepository('User');
		$user = $userRepository->find($entity->get('user'));
		$template->set('USER', generateItemTemplateData($user));
	}

	$template->set(strtoupper($entityNs), generateItemTemplateData($entity));

	return $template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateItemTemplateData(EntityInterface $entity): array {
	$argumentParser = new ArgumentParser;
	$functionParser = new FunctionParser;
	$markdownParser = new MarkdownParser;

	$functionParser->registerFromArray(
		Application::getContentFunctions()
	);

	$attribute = $entity->getAll(['password']);
	$attribute = array_change_key_case($attribute, CASE_UPPER);

	$text = $entity->get('body');
	$text = $functionParser->transform($text);

	$arguments = $argumentParser->parse($entity->get('argv') ?? '');

	$images = $markdownParser->parse($text)['img']['src'] ?? [];
	$images = array_map('htmlentities', $images);

	return [
		'URL' => Application::getEntityURL($entity),
		'ARGV' => $arguments,
		'ATTR' => $attribute,

		'PREV' => false,
		'NEXT' => false,

		'FILE' => [
			'LIST' => $images,
		],

		'BODY' => [
			'TEXT' => function() use($text) {
				return $text;
			},
			'HTML' => function() use($entity) {
				return parseEntityContent($entity);
			}
		]
	];
}

#===============================================================================
# Generate a nested tree from a category data array
#===============================================================================
function generateCategoryDataTree(array $categoryData, $root = 0): array {
	foreach($categoryData as &$category){
		$tree[intval($category['PARENT'])][] = &$category;
		unset($category['PARENT']);
	}

	foreach($categoryData as &$category){
		if (isset($tree[$category['ID']])){
			$category['CHILDS'] = $tree[$category['ID']];
		}
	}

	return $tree[$root] ?? [];
}

#===============================================================================
# Parse entity content
#===============================================================================
function parseEntityContent(EntityInterface $entity): string {
	$text = $entity->get('body');

	$functionParser = new FunctionParser;
	$functionParser->registerFromArray(
		Application::getContentFunctions()
	);

	$text = $functionParser->transform($text);

	if(Application::get('WRAP_EMOTICONS')) {
		$emoticonParser = new EmoticonParser;
		$text = $emoticonParser->transform($text);
	}

	$markdownParser = new MarkdownParser;
	return $markdownParser->transform($text);
}

#===============================================================================
# Parser for datetime formatted strings [YYYY-MM-DD HH:II:SS]
#===============================================================================
function parseDatetime($datetime, $format): string {
	list($date, $time) = explode(' ', $datetime);

	list($DATE['Y'], $DATE['M'], $DATE['D']) = explode('-', $date);
	list($TIME['H'], $TIME['M'], $TIME['S']) = explode(':', $time);

	$unixtime = strtotime($datetime);

	return strtr($format, [
		'[Y]' => $DATE['Y'],
		'[M]' => $DATE['M'],
		'[D]' => $DATE['D'],
		'[H]' => $TIME['H'],
		'[I]' => $TIME['M'],
		'[S]' => $TIME['S'],
		'[W]' => strftime('%A', $unixtime),
		'[F]' => strftime('%B', $unixtime),
		'[DATE]' => $date,
		'[TIME]' => $time,
		'[RFC2822]' => date('r', $unixtime)
	]);
}

#===============================================================================
# Get emoticons with their explanations
#===============================================================================
function getUnicodeEmoticons(): array {
	return (new EmoticonParser)->getEmoticons();
}

#===============================================================================
# Wrapper function for htmlspecialchars()
#===============================================================================
function escapeHTML($string): string {
	return htmlspecialchars($string);
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
# Return truncated string
#===============================================================================
function truncate($string, $length, $replace = '') {
	if(mb_strlen($string) > $length) {
		$truncated = preg_replace("/^(.{0,{$length}}\\b).*/su", '$1', $string);
		$truncated = trim($truncated);

		# The additional trim call is useful, because if $truncated is empty,
		# then there will be an unnecessary space between those two variables
		# if $replace is preceded by a space (for example: " […]").
		return trim("{$truncated}{$replace}");
	}

	return $string;
}

#===============================================================================
# Return excerpt content
#===============================================================================
function excerpt($string, $length = 500, $replace = ' […]') {
	$string = strip_tags($string);
	$string = removeDoubleLineBreaks($string);
	$string = truncate($string, $length, $replace);
	$string = nl2br($string);

	return $string;
}

#===============================================================================
# Return content for meta description
#===============================================================================
function description($string, $length = 200, $replace = ' […]') {
	$string = strip_tags($string);
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

#===========================================================================
# Callback for (CATEGORY|PAGE|POST|USER) content function
#===========================================================================
function getEntityMarkdownLink($ns, $id, $text = null, $info = null): string {
	if(!$entity = Application::getRepository($ns)->find($id)) {
		return sprintf('`{%s: *Reference error*}`', strtoupper($ns));
	}

	$title = $entity->get('name') ?? $entity->get('fullname');
	$href = Application::getEntityURL($entity);
	$text = $text ?: "»{$title}«";

	if($info === null) {
		$info = sprintf('%s »%s«',
			Application::getLanguage()->text(strtolower($ns)),
			$title);
	}

	# Hotfix: Replace double quotes with single quotes because currently we
	# have no sane way to escape double quotes in a string intended to be
	# used as the title for a Markdown formatted link.
	$info = str_replace('"', "'", $info);

	return sprintf('[%s](%s "%s")',	$text, $href, $info);
}

#===========================================================================
# Callback for (CATEGORY|PAGE|POST|USER)_URL content function
#===========================================================================
function getEntityURL($ns, $id): string {
	if(!$entity = Application::getRepository($ns)->find($id)) {
		return sprintf('`{%s_URL: *Reference error*}`', strtoupper($ns));
	}

	return Application::getEntityURL($entity);
}

#===============================================================================
# Function for use in templates to get data of a category
#===============================================================================
function CATEGORY(int $id): array {
	$repository = Application::getRepository('Category');

	if($category = $repository->find($id)) {
		return generateItemTemplateData($category);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a page
#===============================================================================
function PAGE(int $id): array {
	$repository = Application::getRepository('Page');

	if($page = $repository->find($id)) {
		return generateItemTemplateData($page);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a post
#===============================================================================
function POST(int $id): array {
	$repository = Application::getRepository('Post');

	if($post = $repository->find($id)) {
		return generateItemTemplateData($post);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a user
#===============================================================================
function USER(int $id): array {
	$repository = Application::getRepository('User');

	if($user = $repository->find($id)) {
		return generateItemTemplateData($user);
	}

	return [];
}

#===========================================================================
# Register (BAS|FIL)E_URL content functions
#===========================================================================
Application::addContentFunction('BASE_URL',
function($extend = '') {
	return Application::getURL($extend);
});

Application::addContentFunction('FILE_URL',
function($extend = '') {
	return Application::getFileURL($extend);
});

#===========================================================================
# Register (CATEGORY|PAGE|POST|USER)(_URL)? content functions
#===========================================================================
foreach(['CATEGORY', 'PAGE', 'POST', 'USER'] as $function) {
	$entity = ucfirst(strtolower($function));

	// Get Markdown formatted entity link
	Application::addContentFunction($function,
	function($id, $text = null, $title = null) use($entity) {
		return getEntityMarkdownLink($entity, $id, $text, $title);
	});

	// Get URL to entity
	Application::addContentFunction(sprintf('%s_URL', $function),
	function($id) use($entity) {
		return getEntityURL($entity, $id);
	});
}
