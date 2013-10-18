<?php
$archivo = $_FILES['archivo']['name'];
if ($archivo != "") {
    $destino =  "../tmp/".$archivo;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
        
    }
}

