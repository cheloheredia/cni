<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:14/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->buscarrecintostodos();
var_dump($respuesta->matriz);
?>