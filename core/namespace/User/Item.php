<?php
namespace User;

class Item extends \Item {
	const CONFIGURATION = 'USER';

	#===============================================================================
	# Return unique pseudo GUID
	#===============================================================================
	public function getGUID(): string {
		foreach(['id', 'time_insert'] as $attribute) {
			$attributes[] = $this->Attribute->get($attribute);
		}

		return sha1(implode(NULL, $attributes));
	}
}
