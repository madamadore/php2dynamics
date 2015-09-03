<?php
// Here you can initialize variables that will be available to your tests
require_once(dirname(__FILE__) . '/../../entities/Account.class.php');

Codeception\Specify\Config::setIgnoredProperties(['account']);
