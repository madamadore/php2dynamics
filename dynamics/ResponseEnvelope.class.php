<?php

class ResponseEnvelope {
    
    private $_dom;

    public function __construct($xml) {
        
        $this->_dom = new DomDocument();
        $this->_dom->loadXML( $xml );
    }
    
    public function isSuccess() {
        $fault = $this->_dom->getElementsbyTagNameNS("*", "Fault");
        if ( $fault->length == 0 ) {
            return true;
        }
        return false;
    }
    
    public function getErrorMessage() {
        $message = $this->_dom->getElementsbyTagNameNS("*", "Message")->item(0)->nodeValue;
        return $message;
    }
    
    public function getErrorCode() {
        $errorCode = $this->_dom->getElementsbyTagNameNS("*", "ErrorCode")->item(0)->nodeValue;
        return $errorCode;
    }
    
    public function getGeneratedId() {
        $guid = $this->_dom->getElementsbyTagNameNS("*", "KeyValuePairOfstringanyType")->item(0)->nodeValue;
        return substr($guid, 2);
    }
}