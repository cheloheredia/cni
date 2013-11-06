<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->insertarplanificacion(array('turno'=> 1, 'lugar'=>1, 'contenedor'=>1, 'camion'=>1, 'matriz'=>'T-28198-1','cantidad'=>'1','peso'=>'10','bulto'=>1,'baroti'=>'ZNVirtual','destino'=>1,'fecha'=>date('Y-m-d H:i:s')));
var_dump($respuesta);
?>