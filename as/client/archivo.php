<?php
/*include 'client/excelPHP.php';
var_dump(leeExcel('C:\Users\Marcelo\Desktop\MftoImpTtoBol0.xlsx'));*/
/*include 'manifiesto/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:14/wsdl/manifiesto.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->subir(array('documento'=> '\\\127.0.0.1/cni/web/tmp/MftoImpTtoBol0.xls'));
var_dump($respuesta);*/
$archivo = $_FILES['archivo']['name'];
if ($archivo != "") {
    $destino =  "../tmp/".$archivo;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
        
    }
}

