<?php

abstract class Entity extends ReadOnlyEntity {

	var $guid = "00000000-0000-0000-0000-000000000000";
	var $logicalName;
	var $state;
	var $status;
	var $schema;

	public abstract function Create();
	public abstract function Update();

	public static abstract function Delete($guid);

}
