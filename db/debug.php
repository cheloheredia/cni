<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->buscarmanifiestodeconsignatario(array('consignatarioid'=> 25, 'fecha'=> '2013-10-21 19:25:29'));
var_dump($respuesta->matriz);
?>