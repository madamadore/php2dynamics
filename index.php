<!DOCTYPE html>
<?php
error_reporting(E_ALL);
global $_DEBUG_MODE;
$_DEBUG_MODE = false;

require_once "DynamicsIntegrator.class.php";
include_once "functions.php";
include_once "functions/appointments.php";
include_once "functions/contacts.php";
include_once "functions/bookings.php";
include_once "functions/bikemodels.php";
include_once "functions/equipments.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test CRM</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <h1>Test started</h1>

   <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php 

    testGetAppointments(); 

    testGetContacts();
    testGetContact();

    testGetBookings();
    testGetBooking();

    testGetBikeModels();
    testGetBikeModel();

    testGetEquipments();
    testGetEquipment();
    ?>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
</body>
</html>    