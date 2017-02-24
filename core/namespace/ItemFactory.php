<?php
abstract class ItemFactory extends Factory implements FactoryInterface {
	public static function build($itemID): Item {
		if(!$Instance = parent::fetchInstance($itemID)) {
			$Item = (new ReflectionClass(get_called_class()))->getNamespaceName().'\\Item';
			$Instance = parent::storeInstance($itemID, new $Item($itemID, \Application::getDatabase()));
		}

		return $Instance;
	}
}
?>