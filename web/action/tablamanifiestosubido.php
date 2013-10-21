<script type="text/javascript" src="../js/table.js"></script>
<link href="../css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<?php
include '../client/wsdlmanifiesto.php';
$cliente = new SoapClient('http://127.0.0.1:14/wsdl/manifiesto.wsdl',
                          array(
                                'trace' => 1,
                                'cache_wsdl' => WSDL_CACHE_NONE,
                                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                'classmap'=>$GLOBALS['classMapmanifiesto']));
$table = "";
$response = $cliente->mostrarreciensubido(array("fecha" => $_GET['fecha']));
if ($response->error == "OK"){
    $table .= "<table class='example sort01 table-autosort:0 table-autostripe table-stripeclass:alternate ".
    "table-autopage:7 table-page-number:t1page table-page-count:t1pages tabla'><thead><tr style='font-weight:bold;".
    "font-size:12px;color:#fff;'><th>Tipo item</th><th>Agencia</th><th>Nave</th><th>Nro Manifiesto".
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
    $table.="</tbody><tfoot><tr><td colspan='11' style='text-align:center;' class='paginador'>Pagina&nbsp;".
    "<span id='t1page'></span>&nbsp;de <span id='t1pages'></span></td></tr><tr><td class='table-page:previous ".
    "paginador' style='cursor:pointer;'>&lt; &lt; Prev</td><td colspan='10'>&nbsp;</td><td class='table-page:next ".
    "paginador' style='cursor:pointer;'>Sig &gt; &gt;</td></tr></tfoot></table>";
	echo $table;
} else {
	echo '<script type="text/javascript">
			$(function() {
				$("#dialog").html("'.$response->error.'");
				$("#dialog").dialog({
                    title: "Manifiesto maritimo",
                    width: 300,
                    height: 100,
                    modal: true,
                    resizable: false,
                    draggable: true,
					buttons: {
								"Salir": function(){
									window.location.href = "http://127.0.0.1:10/manifiesto.php";
								}
							}
			    });	
			});
	</script>';	
}
        