<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Booking extends Entity {

    public function getLogicalName() {
        return "serviceappointment";
    }
	
	var $avaibility_offline = false;
	var $integration_status = false;
        
    public function getSchema() {
	return array(
                        "description" => "string",
                        "duration" => "int",
                        "regardingobjectid" => array ( "type"=>"guid", "logicalName"=>"contact" ),
                        "resources" => array ( "type"=>"guid_array", "defaultLogicalName"=>"equipment" ),
                        "scheduledstart" => "datetime",
                        "scheduledend" => "datetime",
                        "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
                        "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
                        "state" => "option",
                        "status" => "option",
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
                        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
                        "tb_scheduledbikes" => "float",
                        "tb_servicetype" => "option",
                        "tb_tourid" => array ( "type"=>"guid", "logicalName"=>"appointment" ),
                        "tb_totalamount" => "money",
                        "tb_topbikerevenue" => "money",
                        "tb_tourprice" => "money"
                    );
    }
    
    public function getPrimaryKey() {
        return "activityid";
    }

    public function __construct($subject = "") {
        $this->subject = $subject;
    }
}
