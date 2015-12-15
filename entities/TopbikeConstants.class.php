<?php

class TopBikeConstants {
    
    public static $_LANGUAGE = array( 
        "EN" => "108600000", 
        "NL" => "108600001", 
        "DE" => "108600002", 
        "IT" => "108600003", 
        "ES" => "108600004",
        "FR" => "108600005"
    );
    
    public static $_SEASON_TYPE = array(
        "Low" => "108600000",
        "High" => "108600001"
    );
    
    public static $_GENDER = array(
        "Male" => "108600000",
        "Female" => "108600001",
        "Unisex" => "108600002"
    );
    
    public static $_SERVICE_TYPE = array( 
        "scheduled_tour" => "108600000", 
        "private_tour" => "108600001", 
        "bike_rental" => "108600002" 
    );

    public static $_DAY_PARTS = array(
        "AM" => "108600000",
        "PM" => "108600001",
    );
    
    public static $_BIKE_PRODUCT_ID = "11447825-AE42-E111-90B4-1CC1DE6D3B23";
    
    public static $_SITE_ID = array( 
        "Carlo Botta" => "D68CDA78-D10E-E111-926A-1CC1DE086845",
        "Quattro Cantoni" => "863F6284-D10E-E111-926A-1CC1DE086845",
        "Labicana" => "7809F317-E4B8-E311-8B9A-D89D67638EE8"
    );
    
    public static $_SERVICE_ID = array( 
        "Rental" => "74356AB9-1244-E111-90B4-1CC1DE6D3B23", 
        "Tour" => "1D3E19B5-EFDA-E111-B52D-D4856451DC79" 
    );
    
    public static $_TOUR_TYPE = array(
        "Inside Rome" => "108600000", 
        "Outside Rome" => "108600001",
        "Bike" => "108600002",
        "Accessory" => "108600003",
        "Internal" => "108600004"
    );
    
    public static $_OCCUPATION_FACTOR = array( 
        "Half Day" => "108600000", 
        "Full Day" => "108600001", 
    );
    
    public static $_UNIT_TYPE = array( 
        "People" => "9108d0c4-7e09-4db2-8c9c-256ce196fb46", 
        "Day" => "cd789775-68e1-e211-b713-d4856451dc79"
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
    
    public static $_BIKE_CATEGORIES = array(
        "MTB" => "108600000",
        "Trekking" => "108600001",
        "Speed" => "108600002",
        "Road" => "108600003",
        "Tandem" => "108600004",
        "E-Bike" => "108600005",
        "Folding" => "108600006",
        "Child" => "108600007",
    );
    
    public static $_EQUIPMENT_TYPE = array(
        "Guide" => "108600005",
        "Bike" => "108600000",
        "Inactive/Former Guide" => "108600001",
        "Inactive/Former Equipment" => "108600002",
        "Accessory" => "108600003",
        "Private Tour" => "108600004",
    );
    
    public static $_STATE = array(  // status
        "Open" => "0", 
        "Closed" => "1", 
        "Canceled" => "2", 
        "Scheduled" => "3" 
    );
    
    public static $_STATUS = array( // status reason
        "Tentative" => "2", 
        "Awaiting Deposit" => "1", 
        "Completed" => "8",
        "Canceled" => "9", 
        "Confirmed" => "4", 
        "In Progress" => "6", 
        "No Show" => "10" 
    );
    
    public static $_CHILDSEAT_AVAILABILITY = array( 
        "No" => "108600000", 
        "Back" => "108600001",
        "Front" => "108600002",
        "Back and Front" => "108600003"
    );
    
    public static $_SEASON = array( 
        "Low" => "108600000", 
        "High" => "108600001"
    );
    
    public static $_YES_NO_OPTION = array( 
        "No" => "108600000", 
        "Yes" => "108600001"
    );
    
    public static $_YES_NO = array( 
        "No" => "0", 
        "Yes" => "1"
    );
    
    public static $_BIKE_PROFILES = array( 
        "Low" => "0", 
        "High" => "1"
    );
    
    public static $_DISCOUNT_TYPE = array( 
        "Percentage" => "0", 
        "Fixed Amount" => "1"
    );
    
    public static $_OVERNIGHT_PRODUCTS = array(
        "Low" => "54b9c609-f0cb-e411-80da-c4346bad5034",
        "High" => "aefcaf6-efcb-e411-80da-c4346bad5034"
    );
    
    public static $_STARTING_TIME = array( 
        "08:00" => "108600000", 
        "08:15" => "108600001",
        "08:30" => "108600002",
        "08:45" => "108600003",
        "09:00" => "108600004",
        "09:15" => "108600005",
        "09:30" => "108600006",
        "09:45" => "108600007",
        "10:00" => "108600008",
        "10:15" => "108600009",
        "10:30" => "108600010",
        "10:45" => "108600011",
        "11:00" => "108600012",
        "11:15" => "108600013",
        "11:30" => "108600014",
        "11:45" => "108600015",
        "12:00" => "108600016",
        "12:15" => "108600017",
        "12:30" => "108600018",
        "12:45" => "108600019",
        "13:00" => "108600020",
        "13:15" => "108600021",
        "13:30" => "108600022",
        "13:45" => "108600023",
        "14:00" => "108600024",
        "14:15" => "108600025",
        "14:30" => "108600026",
        "14:45" => "108600027",
        "15:00" => "108600028",
        "15:15" => "108600029",
        "15:30" => "108600030",
        "15:45" => "108600031",
        "16:00" => "108600032",
        "16:15" => "108600033",
        "16:30" => "108600034",
    );
}