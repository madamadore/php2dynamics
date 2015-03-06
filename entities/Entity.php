<?php

abstract class Entity {

	var $guid = "00000000-0000-0000-0000-000000000000";
	var $logicalName;
	var $state;
	var $status;
	var $schema;

	public abstract function Create();
	public abstract function Update();

	public static abstract function RetriveMultiple($conditions = array(), $columns = "all");
	public static abstract function Retrive($guid);
	public static abstract function Delete($guid);

}
