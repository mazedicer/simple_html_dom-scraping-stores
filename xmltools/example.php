<?php

require_once "XMLTools.php";

$xmltools = new XMLTools();

$isvalid = $xmltools->ValidateXML('test_ok.xml','test.xsd'); 
if($isvalid === True) {  
    // return True
    $data = $xmltools->XML2Array('test_ok.xml');
    print_r($data);
} else {
    // returned array of errors
    print_r($isvalid);
}

$isvalid = $xmltools->ValidateXML('test_error.xml','test.xsd'); 
if($isvalid === True) {  
    // return True
    $data = $xmltools->XML2Array('test_error.xml');
    print_r($data);
} else {
    // returned array of errors
    print_r($isvalid);
}
