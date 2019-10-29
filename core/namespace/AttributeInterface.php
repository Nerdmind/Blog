<?php
interface AttributeInterface {
	public function databaseINSERT(\Database $Database);
	public function databaseUPDATE(\Database $Database);
	public function databaseDELETE(\Database $Database);
}
