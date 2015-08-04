php2dynamics is a library that allows to Create, Update, Delete and Retrieve entities from your Microsoft Dynamics 
database into a PHP environment.

It uses SOAP communication and convert MS Dynamics Entities into a PHP Classes also called Entities. Entity class
can be found in php2dynamics/Entity.class.php file.

Each Entity is characterized by a logical name, a primary key and a schema. You can found this information on your 
MS Dynamics database looking at Entities System View.

*Getting started:*

1) Fork php2dynamics folder into your library workspace 
2) Edit php2dynamics/config.json file and insert your own username, password and url.
3) Start create your own classes, by extend Entity class from php2dynamics/Entity.class.php.

If you need some example you can found it in "entities" folder.

*Reference:*

Each schema field can have the following value:

    "datetime"      : a datetime value
    "float"         : a double value
    "int"           : an integer value
    "string"        : a simple or multiple string
    "money"         : a currency value
    "option"        : options always have alphanumerical value, that you can read from you MS Dynamica database.
    "guid"          : a guid is the ID of MS Dynamics Entities.
    "guid_array"    : an array of guid