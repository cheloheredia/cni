<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->insertarmanifiesto(array('itemid'=> 1,'agenciaoperadorid'=> 1,'naveid'=> 1,'viaje'=> 'GI056N','nrmfto'=> '99451','tipotransito'=> 'Transito.Bolivia Desembarque','contenedorid'=> 1,'bl'=> 'IT1589607','puertoembarqueid'=> 1,'purtodestinoid'=> 1,'puertodescargaid'=> 1,'mercanciaid'=> 1,'bruto'=> 2450,'neto'=> 6650,'servicioid'=> 1,'imoid'=> 1,'sellos'=> 'SHP:B4213257','bultos'=> 5,'consignatarioid'=> 1,'estadorecepcion'=> 'Anunciado','periodo'=> '82013','fecha'=> date("Y-m-d H:i:s")));
var_dump($respuesta);
?>