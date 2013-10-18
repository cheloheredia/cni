<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->buscarmanifiestoxfecha(array('fecha'=> '2013-10-18 19:57:33'));
print_r($respuesta);
?>