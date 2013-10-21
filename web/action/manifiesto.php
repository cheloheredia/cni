<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script src="../js/jquery-1.9.1.js"></script>
<script src="../js/jquery-ui-1.10.3.custom.js"></script>
<?php
if ($_POST["action"] == "upload") {
    $tamano = $_FILES["archivo"]['size'];
    $tipo = $_FILES["archivo"]['type'];
    $archivo = $_FILES["archivo"]['name'];
    $prefijo = substr(md5(uniqid(rand())),0,6);  
    if ($archivo != "") {
        $destino =  "../tmp/".$archivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
            $ch = curl_init('http://127.0.0.1:14/client/archivo.php');
            $archivosubir = realpath("../tmp/".$archivo);
            $post = array('extra_info' => '123456','archivo'=>'@'.$archivosubir);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_exec($ch);
            include '../client/wsdlmanifiesto.php';
            $cliente = new SoapClient('http://127.0.0.1:14/wsdl/manifiesto.wsdl',
                                      array(
                                            'trace' => 1,
                                            'cache_wsdl' => WSDL_CACHE_NONE,
                                            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                            'classmap'=>$GLOBALS['classMapmanifiesto']));
            $respuesta = $cliente->subir(array('documento'=> $destino));
            if ($respuesta->error == 'OK') {
                $status = 'Ingresado Correctamente';
                $dir = 'http://127.0.0.1:10/action/tablamanifiestosubido.php?fecha='.$respuesta->fecha;
            } else {
                $status = $respuesta->error;
                $dir = 'http://127.0.0.1:10/manifiesto.php';
            }
        } else {
            $status = "Error al subir el archivo";
        }
        unlink($destino);
    } else {
        $status = "Error al subir archivo";
    }
    echo ' <script type="text/javascript">
            $(function() {
                
                $("#dialog").html("'.$status.'");
                $("#dialog").dialog({
                    title: "Manifiesto maritimo",
                    width: 300,
                    height: 200,
                    modal: true,
                    resizable: false,
                    draggable: true,
                    buttons: {
                                    "Salir": function(){
                                            window.location.href = "'.$dir.'";
                                    }
                            }
                }); 
            });
        
    </script>';
}
?>
</head>
<body>
<div id="dialog"></div> 
</body>
</html>