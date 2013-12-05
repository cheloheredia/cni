<?php
/*include 'client/excelPHP.php';
var_dump(leeExcel('C:\Users\Marcelo\Desktop\26.10.2013 1ER xlxs.xlsx'));*/
include 'planificacion/wsdl.php';
$cliente=new SoapClient('http://127.0.0.1:12/wsdl/dab.wsdl',array( 'trace' => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 'classmap'=>$classMap));
$respuesta=$cliente->mostrarrecintos();
var_dump ($respuesta);
/*include 'client/mPDF.php';
include 'client/PHPMailer.php';
$html = '<html>
<head>
    <title></title>
</head>
<body>
    <div><img src="tmp/CNI.jpg" style="float:left;"><h1><br>CAMARA NACIONAL DE INDUSTRIAS</h1></div>
    <p><H5><DIV ALIGN=right>La Paz, FECHA</DIV>Senhores CONSIGNATARIO:<br><br>Nos es grato comunicarle que su carga arrivo al puerto de Arica con el siguente detalle.<br><br></H5></p>
    <table class="bpmTopnTailC">
        <thead>
            <tr class="headerrow"><th>Nave</th><th>Nro. Manifiesto</th><th>Tipo Contenedor</th><th>Mercancia</th><th>Tara</th><th>Neto</th><th>Bruto</th><th>Tipo Transito</th><th>Operador</th><th>Marca Contenedor</th><th>BL</th><th>Puerto Origen</th><th>Puerto Destino</th><th>Destino Bolivia</th><th>Servicio</th><th>Sellos</th><th>Imo</th><th>Bultos</th></tr>
        </thead>
        <tbody>
            <tr><td>Nave</td><td>Nro. Manifiesto</td><td>Tipo Contenedor</td><td>Mercancia</td><td>Tara</td><td>Neto</td><td>Bruto</td><td>Tipo Transito</td><td>Operador</td><td>Marca Contenedor</td><td>BL</td><td>Puerto Origen</td><td>Puerto Destino</td><td>Destino Bolivia</td><td>Servicio</td><td>Sellos</td><td>Imo</td><td>Bultos</td></tr>
        </tbody>
    </table>
    <p>*Fuente: Terminal Puerto Arica</p>
</body>
</html>';
$content = crearsimple($html);
echo enviarpdf();
//unlink('tmp/filename.pdf');*/



