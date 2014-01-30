<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->buscardabfecha(array('fecha'=> '2014-01-30 19:45:37'));
print_r($respuesta);
?>