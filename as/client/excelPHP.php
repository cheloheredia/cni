<?php
function leeExcel($dir){
	require_once '../phpexcel/Classes/PHPExcel/IOFactory.php';
	$objPHPExcel = PHPExcel_IOFactory::load($dir);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {  
		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
		$var=array();
	   for ($row = 1; $row <= $highestRow; ++ $row) {
		   $aux=array();       
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {            
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$aux[$col] = $cell->getValue();          
			}
			$var[$row-1]=$aux;        
		}  
	}
	return $var;	
}

