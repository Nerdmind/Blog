<?php
abstract class ItemFactory extends Factory {

	#===========================================================================
	# Build instance by ID
	#===========================================================================
	public static function build($itemID): Item {
		if(!$Instance = parent::fetchInstance($itemID)) {
			$Item = (new ReflectionClass(get_called_class()))->getNamespaceName().'\\Item';
			$Instance = parent::storeInstance($itemID, new $Item($itemID, \Application::getDatabase()));
		}

		return $Instance;
	}

	#===========================================================================
	# Build instance by slug
	#===========================================================================
	public static function buildBySlug($slug): Item {
		$Item = (new ReflectionClass(get_called_class()))->getNamespaceName().'\\Item';
		return self::buildByAttribute($Item::getByField('slug', $slug, \Application::getDatabase()));
	}

	#===========================================================================
	# Build instance by Attribute
	#===========================================================================
	public static function buildByAttribute(Attribute $Attribute) {
		if(!$Instance = parent::fetchInstance($Attribute->get('id'))) {
			$Item = (new ReflectionClass(get_called_class()))->getNamespaceName().'\\Item';
			$Instance = parent::storeInstance($Attribute->get('id'), new $Item($Attribute, \Application::getDatabase()));
		}

		return $Instance;
	}
}
