<?php
/**
 * @author: Marcelo Heredia
 * Oct, 2013
*/

/**
* Esta funcion llama a la libreria mPDF, genera el documento pdf y lo guarda en tmp
*
* @param string $html todo el pdf formateado en html
* @param string $nombre nombre con el cual se guardara el pdf
* @return string direccion en tmp del pdf recien creado
*/
function crearsimple($html, $nombre){
	include_once('../mpdf/mpdf.php');
	$mpdf = new mPDF();
	$mpdf->debug = true;
	$mpdf->useSubstitutions=false;
	$stylesheet = file_get_contents('../mpdf/examples/mpdfstyletables.css');
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($html,2);
	$mpdf->Output('../tmp/'.$nombre.'.pdf','F');
	return '../tmp/'.$nombre.'.pdf';
	exit;
}

