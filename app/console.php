<?php
require_once(dirname(__FILE__) . '/../entities/TopBikeConstants.class.php');
require_once(dirname(__FILE__) . '/../entities/Account.class.php');
require_once(dirname(__FILE__) . '/../entities/Appointment.class.php');
require_once(dirname(__FILE__) . '/../entities/Bike.class.php');
require_once(dirname(__FILE__) . '/../entities/BikeModel.class.php');
require_once(dirname(__FILE__) . '/../entities/BikeRental.class.php');
require_once(dirname(__FILE__) . '/../entities/Booking.class.php');
require_once(dirname(__FILE__) . '/../entities/Customer.class.php');
require_once(dirname(__FILE__) . '/../entities/Equipment.class.php');
require_once(dirname(__FILE__) . '/../entities/Price.class.php');
require_once(dirname(__FILE__) . '/../entities/PriceList.class.php');
require_once(dirname(__FILE__) . '/../entities/PriceListItem.class.php');
require_once(dirname(__FILE__) . '/../entities/PrivateTour.class.php');
require_once(dirname(__FILE__) . '/../entities/Product.class.php');
require_once(dirname(__FILE__) . '/../entities/Season.class.php');
require_once(dirname(__FILE__) . '/../entities/Tour.class.php');
require_once(dirname(__FILE__) . '/../entities/TourBooking.class.php');

require_once(dirname(__FILE__) . '/Database.class.php');
require_once(dirname(__FILE__) . '/Data.class.php');

if (1 >= count($argv)) {
    echo "Usage:\n";
    echo "      php app/console <command>\n";
    echo "\n";
    echo "      command list:\n";
    echo "          database:create     create a database script\n";
    exit();
}

$arguments = array();
if (count($argv) > 2) {
    for ($i = 2; $i<count($argv); $i++) {
        $arguments[] = $argv[$i];
    }
}

switch ($argv[1]) {
    case "database:create":
        $database = new Database();
        if (count($arguments)==0) { $arguments = Database::$_TABLES; }
        $database->create($arguments);
        break;
    case "data:import":
        $data = new Data();
        if (count($arguments)==0) { $arguments = array(
            'Appointment',
            'BikeModel',
            'Contact',
            'Equipment',
            'PriceList',
            'PriceListItem',
            'Tour',
        ); }
        $data->import($arguments);
        break;
    case "data:show":
        $data = new Data();
        if (count($arguments)>=2) { 
            foreach($arguments as $guid) {
                $obj = $data->getSingle($arguments[0], $arguments[1]);
                var_dump($obj);
            }
        } else {
            echo "Please give a GUID";
        }
        break;
    case "test:date":
        $now = new DateTime('NOW', new DateTimeZone('UTC'));
        $end = (new DateTime('NOW', new DateTimeZone('UTC')))->add(new DateInterval('P200D'));
        echo $now->format("c") . "\n";
        echo $end->format("c") . "\n";
        break;
}

echo "\n";
