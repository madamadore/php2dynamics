<?php
// Here you can initialize variables that will be available to your tests
require_once(dirname(__FILE__) . '/../../entities/TopBikeConstants.class.php');
require_once(dirname(__FILE__) . '/../../entities/Account.class.php');
require_once(dirname(__FILE__) . '/../../entities/Appointment.class.php');
require_once(dirname(__FILE__) . '/../../entities/Bike.class.php');
require_once(dirname(__FILE__) . '/../../entities/BikeModel.class.php');
require_once(dirname(__FILE__) . '/../../entities/BikeRental.class.php');
require_once(dirname(__FILE__) . '/../../entities/Booking.class.php');
require_once(dirname(__FILE__) . '/../../entities/Customer.class.php');
require_once(dirname(__FILE__) . '/../../entities/Equipment.class.php');
require_once(dirname(__FILE__) . '/../../entities/Price.class.php');
require_once(dirname(__FILE__) . '/../../entities/PriceList.class.php');
require_once(dirname(__FILE__) . '/../../entities/PrivateTour.class.php');
require_once(dirname(__FILE__) . '/../../entities/Tour.class.php');
require_once(dirname(__FILE__) . '/../../entities/TourBooking.class.php');

Codeception\Specify\Config::setIgnoredProperties(['user', 'bikerent', 'tour', 'privatetour']);
