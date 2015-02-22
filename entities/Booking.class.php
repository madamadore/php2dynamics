<?php

class Booking extends Entity {

	var $logicalName = "serviceappointment";
	var $avaibility_offline = false;
	var $integration_status = false;
	var $schema = array(

						    "description" => "string",
						    "duration" => "int",
						    "regardingobjectid" => "guid",
						    "resources" => "guid_array",
							"scheduledstart" => "datetime",
							"scheduledend" => "datetime",
							"serviceid" => "guid",
							"siteid" => "guid",
							"statecode" => "option",
							"statuscode" => "option",
							"subject" => "string",
							"tb_bikeids" => "string",
							"tb_bookingdate" => "datetime",
							"tb_bookingcode" => "string",
							"tb_bookingtype" => "option",
							"tb_contact_gender" => "option",
							"tb_deposit" => "money",
							"tb_language" => "option",
							"tb_materialdetails" => "string",
							"tb_openamount" => "money",
							"tb_participants" => "int",
							"tb_productid" => "guid",
							"tb_scheduledbikes" => "float",
							"tb_servicetype" => "option",
							"tb_tourid" => "guid",
							"tb_totalamount" => "money",
							"tb_topbikerevenue" => "money",
							"tb_tourprice" => "money"
						);

	public function __construct($subject) {
		$this->subject = $subject;
	}
}
