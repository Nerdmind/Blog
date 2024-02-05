<?php
use ORM\EntityInterface;
require '../application.php';

#===========================================================================
# Convert old *content tags* to new *content functions* syntax
#===========================================================================
function convertContentTags(string $text, &$globalMatches): string {
	$url_tags_pattern = '/\{(?<tag>BASE|FILE)\[\"(?<arg>[^"]+)\"\]\}/';
	$entity_tags_pattern_partial = '\{(?<tag>POST|PAGE|USER)\[(?<arg>[0-9]+)\]\}';
	$entity_tags_mdlinks_pattern = '/
		\[(?<text>[^\]]+)\]
		\(
			\s*'.$entity_tags_pattern_partial.'\s*
			(?:\s
				(?<qmark>["\'])
					(?<title>(?:(?:(?!\k<qmark>)).)*)
				\k<qmark>\s*
			)?
		\)
	/x';

	// Convert (BASE|FILE) tags
	$text = preg_replace_callback($url_tags_pattern,
		function($matches) use(&$globalMatches) {
			$formatted = sprintf('{%s_URL: "%s"}',
				$matches['tag'], $matches['arg']);

			$globalMatches[] = [
				$matches[0], $formatted];

			return $formatted;
		}, $text);

	// Convert (POST|PAGE|USER) tags found inside Markdown syntax
	$text = preg_replace_callback($entity_tags_mdlinks_pattern,
		function($matches) use(&$globalMatches) {
			$format = '{%s: %d, "%s"}';
			$params = [
				$matches['tag'],
				$matches['arg'],
				str_replace('"', '\\"', $matches['text'])
			];

			if($linkTitle = $matches['title'] ?? FALSE) {
				$q = $matches['qmark'];
				$format = '{%s: %d, "%s", '.$q.'%s'.$q.'}';
				$params[] = $linkTitle;
			}

			$formatted = sprintf($format, ...$params);

			$globalMatches[] = [
				$matches[0], $formatted];

			return $formatted;
		}, $text);

	// Convert (POST|PAGE|USER) tags found anywhere else
	$text = preg_replace_callback(sprintf('/%s/x', $entity_tags_pattern_partial),
		function($matches) use(&$globalMatches) {
			$formatted = sprintf('{%s_URL: %s}',
				$matches['tag'], $matches['arg']);

			$globalMatches[] = [
				$matches[0], $formatted];

			return $formatted;
		}, $text);

	return $text;
}

#===========================================================================
# Print matches for a specific entity
#===========================================================================
function printMatches(EntityInterface $Entity, array &$matches): void {
	printf("%s\n", str_repeat('-', 100));
	printf("%s (#%d):\n", get_class($Entity), $Entity->getID());
	printf("»%s«\n", $Entity->get('name') ?? $Entity->get('fullname'));
	printf("%s\n", str_repeat('-', 100));

	foreach($matches as $match) {
		printf("<-- %s\n", $match[0]);
		printf("--> %s\n\n", $match[1]);
	}
}

#===========================================================================
# Send Cache-Control header and change Content-Type to plain text
#===========================================================================
HTTP::responseHeader('Cache-Control', 'no-cache');
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_TEXT);

#===========================================================================
# Set "commit" variable based on GET parameter or CLI argument
#===========================================================================
$commit = FALSE;
if(isset($_GET['commit']) OR
	(isset($argv[1]) AND $argv[1] === 'commit')) {
	$commit = TRUE;
}

#===========================================================================
# Print header information
#===========================================================================
if(!$commit) {
	printf("%s\n", str_repeat('%', 100));
	print("!!! DRY-RUN !!!\n");
	print("To commit the changes, pass the CLI argument 'commit'!\n");
	printf("%s\n\n", str_repeat('%', 100));
}

foreach(['Category', 'Page', 'Post', 'User'] as $entityName) {
	$Repository = Application::getRepository($entityName);

	foreach($Repository->getAll() as $Entity) {
		unset($matches);

		$content = $Entity->get('body');
		$content = convertContentTags($content, $matches);

		if($matches) {
			$foundMatches = TRUE;

			if($commit) {
				$Entity->set('body', $content);
				$Repository->update($Entity);
			} else {
				printMatches($Entity, $matches);
			}
		}
	}
}

if(!isset($foundMatches)) {
	print("Found no matches for old content tags in your entities' content. Nothing to do!");
} else {
	$commit && print("Replace operation complete!");
}

print("\n");
