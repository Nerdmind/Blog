<?php
namespace Parser;

interface ParserInterface {
	public function parse(string $text): array;
	public function transform(string $text): string;
}
