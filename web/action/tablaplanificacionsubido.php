<script type="text/javascript" src="../js/table.js"></script>
<link href="../css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<?php
include '../client/wsdlplanificacion.php';
$cliente = new SoapClient('http://127.0.0.1:14/wsdl/planificacion.wsdl',
                          array(
                                'trace' => 1,
                                'cache_wsdl' => WSDL_CACHE_NONE,
                                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                'classmap'=>$GLOBALS['classMapplanificacion']));
$table = "";
$response = $cliente->mostrarreciensubido(array("fecha" => $_GET['fecha']));
if ($response->error == "OK"){
    for ($i = 0; $i < sizeof($response->planificacion); $i++) { 
        $table .= "<table class='example sort01 table-autosort:0 table-autostripe table-stripeclass:alternate ".
        "table-autopage:7 table-page-number:t1page table-page-count:t1pages tabla'><thead><tr style='font-weight:bold;".
        "font-size:12px;color:#fff;'><th  COLSPAN=12>".$response->planificacion[$i]->turno."</th></tr></thead>";
        for ($j = 0; $j < sizeof($response->planificacion[$i]->almacen); $j++) { 
            $table .= "<thead><tr style='font-weight:bold;font-size:12px;color:#fff;'><th  COLSPAN=12>".
            $response->planificacion[$i]->almacen[$j]->almacen."</th></tr></thead><thead><tr style='font-weight:bold;".
            "font-size:12px;color:#fff;'><th>Contenedor</th><th>Tipo</th><th>Placa</th><th>Matriz".
            "</th><th>Cantidad</th><th>Peso</th><th>Descripcion</th><th>Tipo Bulto</th><th>Lugar</th><th>Baroti</th>".
            "<th>Transportista</th><th>Destino</th></tr></thead><tbody>";
            for ($k = 0; $k < sizeof($response->planificacion[$i]->almacen[$j]->tabla); $k++) { 
                $table.="<tr style='font-size:10px;'><td>".$response->planificacion[$i]->almacen[$j]->tabla[$k]->contenedor.
                "</td><td>".$response->planificacion[$i]->almacen[$j]->tabla[$k]->tipo."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->placa."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->matriz."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->cantidad."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->peso."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->descripcion."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->tipobulto."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->lugar."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->baroti."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->transportista."</td><td>".
                $response->planificacion[$i]->almacen[$j]->tabla[$k]->destino."</td></tr>";  
            }
        }
        $table.="</tbody><tfoot><tr><td colspan='12' style='text-align:center;' class='paginador'>Pagina&nbsp;".
        "<span id='t1page'></span>&nbsp;de <span id='t1pages'></span></td></tr><tr><td class='table-page:previous ".
        "paginador' style='cursor:pointer;'>&lt; &lt; Prev</td><td colspan='10'>&nbsp;</td><td class='table-page:next ".
        "paginador' style='cursor:pointer;'>Sig &gt; &gt;</td></tr></tfoot></table>";
    }
	echo $table;
    /*$response = $cliente->generarpdfyenviar(array("fecha" => $_GET['fecha']));
    if ($response->error != 'OK') {
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
    }*/
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
        