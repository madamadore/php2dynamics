<?php

class TopBikeConstants {
    
    public static $_LANGUAGE = array( 
        "EN" => "108600000", 
        "NL" => "108600001", 
        "DE" => "108600002", 
        "IT" => "108600003", 
        "ES" => "108600004" 
    );
    
    public static $_SERVICE_TYPE = array( 
        "scheduled_tour" => "108600000", 
        "private_tour" => "108600001", 
        "bike_rental" => "108600002" 
    );

    public static $_BIKE_PRODUCT_ID = "11447825-AE42-E111-90B4-1CC1DE6D3B23";
    
    public static $_SITE_ID = array( 
        "Carlo Botta" => "D68CDA78-D10E-E111-926A-1CC1DE086845",
        "Quattro Cantoni" => "863F6284-D10E-E111-926A-1CC1DE086845",
        "Labicana" => "7809F317-E4B8-E311-8B9A-D89D67638EE8"
    );
    
    public static $_SERVICE_ID = array( 
        "Rental" => "74356ab9-1244-e111-90b4-1cc1de6d3b23", 
        "Tour" => "1D3E19B5-EFDA-E111-B52D-D4856451DC79" 
    );

    public static $_BOOKING_TYPE = array( 
        "Web" => "108600000", 
        "Direct" => "108600003", 
        "Partner" => "108600001",
        "Third Party" => "108600002", 
        "Local Party" => "108600004"
    );
    
    public static $_BIKE_BRAND = array(
        "Cannondale" => "108600008",
        "KTM" => "108600007",
        "Tern" => "108600003",
        "Staiger" => "108600004",
        "Hercules" => "108600006",
        "Haibike" => "108600005",
        "Ghost" => "108600002",
        "Bergamont" => "108600000",
        "Carraro" => "108600001"
    );
    
    public static $_EQUIPMENT_TYPE = array(
        "Guide" => "108600005",
        "Bike" => "108600000",
        "Inactive/Former Guide" => "108600001",
        "Inactive/Former Equipment" => "108600002",
        "Accessory" => "108600003"
    );
}