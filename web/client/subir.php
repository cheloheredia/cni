<?php
include 'ini.php';
$tamano = $_FILES["archivo"]['size'];
$tipo = $_FILES["archivo"]['type'];
$archivo = $_FILES["archivo"]['name'];
$prefijo = substr(md5(uniqid(rand())),0,6);  
if ($archivo != "") {
    $destino =  "../tmp/".$archivo;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
        $ch = curl_init($asdir.'/client/archivo.php');
        $archivosubir = realpath("../tmp/".$archivo);
        $post = array('extra_info' => '123456','archivo'=>'@'.$archivosubir);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        switch ($_POST["opt"]) {
            case 'manifiesto':
                include '../client/wsdlmanifiesto.php';
                $cliente = new SoapClient($manifiestodir,
                                          array(
                                                'trace' => 1,
                                                'cache_wsdl' => WSDL_CACHE_NONE,
                                                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                                'classmap'=>$GLOBALS['classMapmanifiesto']));
                $respuesta = $cliente->subir(array('documento'=> $destino));
                break;
            case 'dab':
                include '../client/wsdldab.php';
                $cliente = new SoapClient($dabdir,
                                          array(
                                                'trace' => 1,
                                                'cache_wsdl' => WSDL_CACHE_NONE,
                                                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                                'classmap'=>$GLOBALS['classMapdab']));
                $respuesta = $cliente->subir(array('documento' => $destino, 'recinto' => $_POST['recinto']));
                break;
            default:
                # code...
                break;
        }
        if ($respuesta->error == 'OK') {
            $status = $respuesta->fecha;
        } else {
            $status = $respuesta->error;
        }
    } else {
        $status = "Error al subir el archivo";
    }
    unlink($destino);
} else {
    $status = "Error al subir archivo";
}
echo $status;

