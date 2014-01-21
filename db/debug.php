<?php
include 'db/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->insertarreportedab(array(
                                                                 'viaje'=> '42220131775',
                                                                 'ingreso'=> '466920',
                                                                 'enbarque'=> 'ROT25722',
                                                                 'item'=> '1',
                                                                 'fechaingreso'=> '2013-04-15',
                                                                 'fechabalanza'=> '2013-05-03',
                                                                 'fecharecepcion'=> '2013-05-06',
                                                                 'fechasalida'=> '',
                                                                 'consignatarioid'=> 1,
                                                                 'bultosmanifiesto'=> '1',
                                                                 'pesomanifestado'=> '7500',
                                                                 'bultosrecevidos'=> '1',
                                                                 'pesorecivido'=> '6860',
                                                                 'saldopeso'=> '6860',
                                                                 'saldobultos'=> '1',
                                                                 'descripcionid'=> 1,
                                                                 'almacenid'=> 1,
                                                                 'registrodepositoid'=> 1,
                                                                 'fechavencimiento'=> '',
                                                                 'estadoid'=> 1,
                                                                 'dvi'=> null,
                                                                 'camionid'=> 1,
                                                                 'chasis'=> null,
                                                                 'fecha'=> date("Y-m-d H:i:s")
                                                                 ));
print_r($respuesta);
?>