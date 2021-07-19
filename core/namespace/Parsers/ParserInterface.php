<?php
namespace Parsers;

interface ParserInterface {
	public function parse(string $text): array;
	public function transform(string $text): string;
}
