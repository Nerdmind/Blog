<?php
namespace Parsers;
use Parsedown;

class MarkdownParser implements ParserInterface {
	private $Parsedown;

	#===========================================================================
	# Initialize
	#===========================================================================
	public function __construct() {
		$this->Parsedown = new Parsedown();
		$this->Parsedown->setUrlsLinked(FALSE);
	}

	#===========================================================================
	# Parse Markdown (currently only images)
	#===========================================================================
	public function parse(string $text): array {
		$image = '#\!\[(.*)\]\((.*)(?:\s[\'"](.*)[\'"])?\)#U';

		if(preg_match_all($image, $text, $matches)) {
			$data['img']['src'] = $matches[2];
			$data['img']['alt'] = $matches[1];
			$data['img']['title'] = $matches[3];
		}

		return $data ?? [];
	}

	#===========================================================================
	# Transform Markdown to HTML
	#===========================================================================
	public function transform(string $text): string {
		return $this->Parsedown->text($text);
	}
}
