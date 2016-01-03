<?php
require_once dirname(__FILE__) . "/../php2dynamics/Entity.class.php";

class Booking extends Entity {

    var $avaibility_offline = false;
    var $integration_status = false;
        
    public function getLogicalName() {
        return "serviceappointment";
    }
        
    public function getSchema() {
	return array(
            "description" => "string",
            "duration" => "int",
            "regardingobjectid" => array ( "type"=>"guid", "logicalName"=>"contact" ),
            "resources" => array ( "type"=>"guid_array", "logicalName"=>"equipment" ),
            "scheduledstart" => "datetime",
            "scheduledend" => "datetime",
            "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
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
            "tb_commission" => "money",
            "tb_balance" => "money",
            "tb_topbikerevenue" => "money",
            "tb_openamount" => "money",
            "tb_participants" => "int",
            "tb_servicetype" => "option",
            "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_tourid" => array ( "type"=>"guid", "logicalName"=>"appointment" ),
            "tb_scheduledbikes" => "float",
            "tb_totalamount" => "money",
            "tb_topbikerevenue" => "money",
            "tb_tourprice" => "money",
            "tb_women" => "decimal",
            "tb_men" => "decimal",
            "tb_children" => "decimal",
            "tb_child_seats" => "decimal",
            "tb_ebikes_number" => "decimal",
            "description" => "string",
            "tb_creditcardrequirednew" => "option",
            "tb_pricelistid" => array ( "type"=>"guid", "logicalName"=>"pricelevel" )
        );
    }
    
    public function getPrimaryKey() {
        return "activityid";
    }

    public function __construct($subject = "") {
        $this->subject = $subject;
    }
}
