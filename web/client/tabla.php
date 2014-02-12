<?php
if ($_POST['opt'] == 'manifiesto') {
    include 'wsdlmanifiesto.php';
    $cliente = new SoapClient('http://127.0.0.1:14/wsdl/manifiesto.wsdl',
                              array(
                                    'trace' => 1,
                                    'cache_wsdl' => WSDL_CACHE_NONE,
                                    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                    'classmap'=>$GLOBALS['classMapmanifiesto']));
    $table = "";
    $response = $cliente->mostrarreciensubido(array("fecha" => $_POST['fecha']));
    if ($response->error == "OK"){
        $table .= "<table class='example sort01 table-autosort:0 table-autostripe table-stripeclass:alternate ".
        "table-autopage:7 table-page-number:t1page table-page-count:t1pages tabla'><thead><tr style='font-weight:bold;".
        "font-size:12px;'><th>Tipo item</th><th>Agencia</th><th>Nave</th><th>Nro Manifiesto".
        "</th><th>Tipo Transito</th><th>Ctr o Marcas</th><th>Tipo Ctr</th><th>Operador</th><th>BL</th>".
        "<th>Puerto Embarque</th><th>Puerto Descarga</th><th>Puerto Destino</th><th>Mercancia</th><th>Tara</th>".
        "<th>Neto</th><th>Bruto</th><th>Servicio</th><th>Imo</th><th>Sellos</th><th>Bultos</th><th>Consignatario</th>".
        "<th>Estado Recepcion</th><th>Periodo</th></tr></thead><tbody>";                
        foreach ($response->manifiestomaritimo as $row) {
            $table.="<tr style='font-size:10px;'><td>".$row->item."</td><td>".$row->agencia."</td><td>".$row->nave.
            "</td><td>".$row->nromfto."</td><td>".$row->tipotransito."</td><td>".$row->contenedor."</td><td>".
            $row->tipocontenedor."</td><td>".$row->operador."</td><td>".$row->bl."</td><td>".$row->puertoembarque.
            "</td><td>".$row->puertodescarga."</td><td>".$row->puertodestino."</td><td>".$row->mercancia."</td><td>".
            $row->tara."</td><td>".$row->neto."</td><td>".$row->bruto."</td><td>".$row->servicio."</td><td>".
            $row->imo."</td><td>".$row->sellos."</td><td>".$row->bultos."</td><td>".$row->consignatario."</td><td>".
            $row->estado."</td><td>".$row->periodo."</td></tr>";  
        }
        $table.="</tbody></table>";
        echo $table;
        $response = $cliente->generarpdfyenviar(array("fecha" => $_POST['fecha']));
        if ($response->error != 'OK') {
            echo '<br><span class="mensaje">'.$response->error.'</span><br>';
        }
    } else {
        echo '<br><span class="mensaje">'.$response->error.'</span><br>';
    }
}
if ($_POST['opt'] == 'dab') {
    include 'wsdldab.php';
    $cliente = new SoapClient('http://127.0.0.1:14/wsdl/dab.wsdl',
                              array(
                                    'trace' => 1,
                                    'cache_wsdl' => WSDL_CACHE_NONE,
                                    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                    'classmap'=>$GLOBALS['classMapdab']));
    $table = "";
    $response = $cliente->mostrardabreciensubido(array("fecha" => $_POST['fecha']));
    if ($response->error == "OK"){
        echo '<br><span class="mensaje">'.$response->dab[0]->recinto.'</span><br>';
        $table .= "<table class='example sort01 table-autosort:0 table-autostripe table-stripeclass:alternate ".
        "table-autopage:7 table-page-number:t1page table-page-count:t1pages tabla'><thead><tr style='font-weight:bold;".
        "font-size:12px;'><th>Nro. Viaje</th><th>Nro. Ingreso</th><th>Item</th><th>Fecha Ing.".
        "</th><th>Fecha Balanza</th><th>Fecha Parte de Recepcion</th><th>Fecha Salida Almacenes</th><th>Consignatario</th><th>Bultos Man.</th>".
        "<th>Peso Man.</th><th>Bultos Rec.</th><th>Peso Rec.</th><th>Saldo Peso</th><th>Saldo Bultos</th>".
        "<th>Descripcion</th><th>Almacen</th><th>Reg. Deposito</th><th>Tipo Mercancia</th><th>Fec. Vencimiento</th><th>Estado</th><th>Nro. DUI</th>".
        "<th>Nro. Placa</th><th>Nro. Chasis</th></tr></thead><tbody>";                
        foreach ($response->dab[0]->reporte as $row) {
            $table.="<tr style='font-size:10px;'><td>".$row->viaje."</td><td>".$row->nroingreso."</td><td>".$row->item.
            "</td><td>".$row->fechaingreso."</td><td>".$row->fechabalanza."</td><td>".$row->fecharecepcion."</td><td>".
            $row->fechasalida."</td><td>".$row->consignatario."</td><td>".$row->bultosman."</td><td>".$row->pesoman.
            "</td><td>".$row->bultorec."</td><td>".$row->pesorec."</td><td>".$row->saldopeso."</td><td>".
            $row->saldobulto."</td><td>".$row->mercancia."</td><td>".$row->almacen."</td><td>".$row->deposito."</td><td>".
            $row->tmercancia."</td><td>".$row->fechavenc."</td><td>".$row->estado."</td><td>".$row->dui."</td><td>".
            $row->camion."</td><td>".$row->chasis."</td></tr>";  
        }
        $table.="</tbody></table>";
        echo $table;
        $response = $cliente->generarpdfyenviar(array("fecha" => $_POST['fecha']));
        if ($response->error != 'OK') {
            echo '<br><span class="mensaje">'.$response->error.'</span><br>';
        }
    } else {
        echo '<br><span class="mensaje">'.$response->error.'</span><br>';
    }
}


