<?php
require_once dirname(__FILE__) . "/Booking.class.php";

class TourBooking extends Booking {

    var $avaibility_offline = false;
    var $integration_status = false;
        
    public function getLogicalName() {
        return "serviceappointment";
    }
        
    public function getSchema() {
	return array(
            "description" => "string",
            "regardingobjectid" => array ( "type"=>"guid", "logicalName"=>"contact" ),
            "resources" => array ( "type"=>"guid_array", "logicalName"=>"equipment" ),
            "scheduledstart" => "datetime",
            "scheduledend" => "datetime",
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
            "tb_servicetype" => "option",
            "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_tourid" => array ( "type"=>"guid", "logicalName"=>"appointment" ),
            "tb_scheduledbikes" => "float",
            "tb_totalamount" => "money",
            "tb_topbikerevenue" => "money",
            "tb_tourprice" => "money",
            "tb_children" => "float",
            "tb_childamount" => "money",
            "tb_ebikeamount" => "money",
            "tb_child_seats" => "float",
            "tb_ebikes_number" => "float"
        );
    }
    
    public function getPrimaryKey() {
        return "activityid";
    }

    public function __construct($subject = "") {
        $this->subject = $subject;
        $this->tb_servicetype = TopBikeConstants::$_SERVICE_TYPE[ "scheduled_tour" ];
        $this->tb_bookingtype = TopBikeConstants::$_BOOKING_TYPE[ "Web" ];
        $this->serviceid = TopBikeConstants::$_SERVICE_ID[ "Tour" ];
    }
    
}
