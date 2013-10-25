<?php
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

