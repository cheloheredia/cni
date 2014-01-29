<?php
/**
 * @author Marcelo Heredia
 * Jan, 2014
*/

/**
 * @var string
 */
$criterio = strtolower($_GET["term"]);

/**
 * @var string
 */
$opt = $_GET['opt'];
include('ini.php');
if (!$criterio) return;
if (!$opt) return;
?>
[<?php
switch ($opt) {
    case 'recinto':
        include('wsdldab.php');
        $cliente = new SoapClient($dabdir,
                                  array('trace' => 1,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'classmap'=> $GLOBALS['classMapdab']));
        $respuesta = $cliente->mostrarrecintos(array('recinto' => $criterio));
        if ($respuesta->error == 'OK') {
            for ($i = 0; $i < sizeof($respuesta->recinto); $i++) { 
                $items[$i] = $respuesta->recinto[$i]->recinto;
            }
        } else {
            $items[0] = $respuesta->error;
        }
        break;
    default:
        break;
}
$contador = 0;
foreach ($items as $id => $item) 
{
	if ($contador++ > 0) print ", ";
	print "{ \"label\" : \"$item\", \"value\" : { \"opt\" : \"$opt\", \"id\" : $id } }";
}
?>]

