<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/

$archivo = $_FILES['archivo']['name'];
if ($archivo != "") {
    $destino =  "../tmp/".$archivo;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
        
    }
}

