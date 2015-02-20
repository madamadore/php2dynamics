<?php

class Contact extends Entity {

	private $logicalName = "contact";
	private $schema = array(
		"fullname" => array ( "string", "required" ),
		"firstname" => array ( "string" ),
		"lastname" => array ( "string" ),
		"emailaddress" => array ( "string" ),
		"description" => array ( "string" )
	);

	private $fullname;
	private $firstname;
	private $lastname;
	private $emailaddress;
	private $mobilephone;
	private $description;

	function __construct($fullname) {
		$this->setFullname( $fullname );
	}

	/**
	 * @return mixed
	 */
	public function getEmailaddress() {
		return $this->emailaddress;
	}

	/**
	 * @param mixed $emailaddress
	 */
	public function setEmailaddress( $emailaddress ) {
		$this->emailaddress = $emailaddress;
	}

	/**
	 * @return mixed
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param mixed $firstname
	 */
	public function setFirstname( $firstname ) {
		$this->firstname = $firstname;
	}

	/**
	 * @return mixed
	 */
	public function getFullname() {
		return $this->fullname;
	}

	/**
	 * @param mixed $fullname
	 */
	public function setFullname( $fullname ) {
		$this->fullname = $fullname;
	}

	/**
	 * @return mixed
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param mixed $lastname
	 */
	public function setLastname( $lastname ) {
		$this->lastname = $lastname;
	}

	/**
	 * @return mixed
	 */
	public function getMobilephone() {
		return $this->mobilephone;
	}

	/**
	 * @param mixed $mobilephone
	 */
	public function setMobilephone( $mobilephone ) {
		$this->mobilephone = $mobilephone;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}
}
