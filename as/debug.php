<?php
/*include 'client/excelPHP.php';
var_dump(leeExcel('C:\Users\Marcelo\Desktop\MftoImpTtoBol0.xlsx'));*/
include 'manifiesto/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:14/wsdl/manifiesto.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->subir(array('documento'=> 'C:\Users\Marcelo\Desktop\MftoImpTtoBol0.xlsx'));
var_dump($respuesta);

