php2dynamics is a library that allows to Create, Update, Delete and Retrieve entities from your Microsoft Dynamics 
database into a PHP environment.

It uses SOAP communication and converts MS Dynamics Entities into a PHP Classes, also called Entities. Entity class
can be found in php2dynamics/entities folder and extends Entity.class.php file.

Each Entity is characterized by a logical name, a primary key and a schema. You can found this information on your 
MS Dynamics database looking in Entities System View panel.

# Getting started:

1) Fork php2dynamics folder into your library workspace 
2) Edit php2dynamics/config.json file and insert your own username, password and url.
3) Start create your own classes, by extend Entity class from php2dynamics/Entity.class.php.

If you need some example you can found it in "entities" folder.

# Reference:

Each Entity has following methods and attributes:

#### Attributes:

**Logical Name**: this is the name of the entity on MS Dynamics
**Primary Key**: the field used by MS Dynamics to identify the entity. This is always a GUID value.
**Schema**: the schema is an array of fields that composes the entity. 
    Each field have to be characterized by his type.

    Each schema field can have the following value:

        "datetime"      : a datetime value
        "float"         : a double value
        "int"           : an integer value
        "string"        : a simple or multiple string
        "money"         : a currency value
        "option"        : options always have alphanumerical value, that you can read from you MS Dynamics database.
        "guid"          : a guid is the ID of MS Dynamics Entities.
        "guid_array"    : an array of guid

    The "guid" and "guid_array" values are expressed as an array with and "type" and "logicalName" parameter
**State** and **Status**: These two values represents the status of Entity. These fields are update by the `*UpdateState*` function.
**Guid**: a guid is an alphanumerical string that identifies the entity.

#### Functions:

**public function Create():** 
Create a new entity and returns his GUID, or the error string.

**public function Update():**  
Update the entity identified by his GUID and returns `*true*` or the error string.

**public function Delete():**  
Delete the entity identified by his GUID and returns `*true*` or the error string.

**public function RetrieveSingle($guid):**
Retrieve an entity by his GUID. The entity returned must be mapped by a schema, or an empty object will be returned.

**public function RetrieveMultiple($conditions):**
Retrieve one or more entity by an array of conditions. Each condition is an array of three values:
- attribute: the column name
- operator: Equal, Like, GreaterThan, LessThan, NotEqual
- value: the search parameter.

    e.g.: 
    $conditions = array( 
        array("attribute" => "name", "operator" => "Like", "value" => "Something%")
    );

**public function UpdateState():**
Update status of current entity.