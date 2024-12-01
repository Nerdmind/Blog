<?php
namespace Parsers;

class ArgumentParser implements ParserInterface {

	#===========================================================================
	# Parse arguments (*without* duplicates)
	#===========================================================================
	public function parse(string $text): array {
		foreach(explode('|', $text) as $delimiter) {
			$part = explode('=', $delimiter);

			$argumentK = $part[0] ?? null;
			$argumentV = $part[1] ?? true;

			if(preg_match('#^[[:word:]]+$#', $argumentK)) {
				$arguments[strtoupper($argumentK)] = $argumentV;
			}
		}

		return $arguments ?? [];
	}

	#===========================================================================
	# Transform arguments (not implemented)
	#===========================================================================
	public function transform(string $text): string {
		return '';
	}
}
