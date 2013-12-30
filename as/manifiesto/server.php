<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
require_once('wsdl.php');
require_once('../client/wsdldb.php');
require_once('../ini/ini.php');
class manifiesto {

	/**
	* Esta funcion inicia todos los clientes necesarios para el funcionamiento de la clase manifiesto.
	*/
	public function __construct(){
		$this->clientdb = new SoapClient($GLOBALS['dbsdir'].'/wsdl/db.wsdl',
		                                 array('trace' => 1,
		                                       'cache_wsdl' => WSDL_CACHE_NONE,
		                                       'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
		                                       'classmap' => $GLOBALS['classMapdb']));
		include_once '../client/excelPHP.php';
		include_once '../client/mPDF.php';
		include_once '../client/PHPMailer.php';
	}

	/**
	* Esta funcion realiza la sustitucion de tildes de una cadena.
	*
	* @param string $cadena la cadena a la cual hay que cambiar tildes
	* @return string $texto que es la cadena cambiada
	*/
	public function quitar_tildes($cadena) {
		$no_permitidas = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬",
		                        "Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯",
		                        "Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas = array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u",
		                     "c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

	/**
	* Esta funcion realiza la subida de los datos del manifiesto a la base de datos.
	*
	* @param string $input=>documento direccion donde se encuentra el documento excel subido
	* @return string $subirsalidas->error OK si no ocurrio ningun error
	*         datetime $subirsalidas->fecha fecha en la que se subio el manifiesto
	*/
	public function subir($input) {
		$res = new subirsalidas();
		$fecha = date("Y-m-d H:i:s");
		$resexcel = leeExcel($input->documento);
		for ($i = 1; $i < sizeof($resexcel); $i++) {
			$texto = strtoupper($resexcel[$i][1]);
			$resdb = $this->clientdb->buscaritem(array('item'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertaritem(array('item'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscaritem(array('item'=> $texto));
				} else {
					$res->error = 'Existe un error con el item, favor vuelva a intentarlo';
					break;
				}
			}
			$itemid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][2]);
			$resdb = $this->clientdb->buscaragencia(array('agencia'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertaragencia(array('agencia'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscaragencia(array('agencia'=> $texto));
				} else {
					$res->error = 'Existe un error con la agencia, favor vuelva a intentarlo';
					break;
				}
			}
			$agenciaid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][12]);
			$resdb = $this->clientdb->buscaroperador(array('operador'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertaroperador(array('operador'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscaroperador(array('operador'=> $texto));
				} else {
					$res->error = 'Existe un error con el operador, favor vuelva a intentarlo';
					break;
				}
			}
			$operadorid = $resdb->matriz[0]->columnas[0];
			$resdb = $this->clientdb->buscaragenciaoperador(array(
			                                                'operadorid'=> $operadorid,
			                                                'agenciaid'=> $agenciaid));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertaragenciaoperador(array(
				                                                  'operadorid'=> $operadorid, 
				                                                  'agenciaid'=> $agenciaid));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscaragenciaoperador(array(
					                                                'operadorid'=> $operadorid,
					                                                'agenciaid'=> $agenciaid));
				} else {
					$res->error = 'Existe un error con la relacion entre agencia y operador, favor vuelva a intentarlo';
					break;
				}
			}
			$agenciaoperadorid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][4]);
			$resdb = $this->clientdb->buscarnave(array('nave'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarnave(array(
				                                       'nave'=> $texto,
				                                       'operadorid'=> $operadorid));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarnave(array('nave'=> $texto));
				} else {
					$res->error = 'Existe un error con la nave, favor vuelva a intentarlo';
					break;
				}
			}
			$naveid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][10]);
			$resdb = $this->clientdb->buscartipocontenedor(array('tipo'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertartipocontenedor(array('tipo'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscartipocontenedor(array('tipo'=> $texto));
				} else {
					$res->error = 'Existe un error con el tipo de contenedor, favor vuelva a intentarlo';
					break;
				}
			}
			$tipoid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][9]);
			$resdb = $this->clientdb->buscarcontenedor(array('contenedor'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarcontenedor(array(
				                                             'contenedor'=> $texto,
				                                             'tipoid'=> $tipoid,
				                                             'peso'=> $resexcel[$i][18]));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarcontenedor(array('contenedor'=> $texto));
				} else {
					$res->error = 'Existe un error con el contenedor, favor vuelva a intentarlo';
					break;
				}
			}
			$contenedorid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][14]);
			$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarpuerto(array('puerto'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
				} else {
					$res->error = 'Existe un error con el puerto de embarque, favor vuelva a intentarlo';
					break;
				}
			}
			$puertoembarqueid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][15]);
			$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarpuerto(array('puerto'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
				} else {
					$res->error = 'Existe un error con el puerto de descarga, favor vuelva a intentarlo';
					break;
				}
			}
			$puertodescargaid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][16]);
			$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarpuerto(array('puerto'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
				} else {
					$res->error = 'Existe un error con el puerto destino, favor vuelva a intentarlo';
					break;
				}
			}
			$puertodestinoid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($this->quitar_tildes($resexcel[$i][17]));
			$resdb = $this->clientdb->buscarmercancia(array('mercancia'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarmercancia(array('mercancia'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarmercancia(array('mercancia'=> $texto));
				} else {
					$res->error = 'Existe un error con la mercancia, favor vuelva a intentarlo';
					break;
				}
			}
			$mercanciaid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][21]);
			$resdb = $this->clientdb->buscarservicio(array('servicio'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarservicio(array('servicio'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarservicio(array('servicio'=> $texto));
				} else {
					$res->error = 'Existe un error con el servicio, favor vuelva a intentarlo';
					break;
				}
			}
			$servicioid = $resdb->matriz[0]->columnas[0];
			$texto = $resexcel[$i][22];
			if ($texto == null) {
				$texto = '';
			}
			$resdb = $this->clientdb->buscarimo(array('imo'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarimo(array('imo'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarimo(array('imo'=> $texto));
				} else {
					$res->error = 'Existe un error con el imo, favor vuelva a intentarlo';
					break;
				}
			}
			$imoid = $resdb->matriz[0]->columnas[0];
			$texto = strtoupper($resexcel[$i][25]);
			$resdb = $this->clientdb->buscarconsignatario(array('consignatario'=> $texto));
			if ($resdb->error == 1) {
				$resdb = $this->clientdb->insertarconsignatario(array('consignatario'=> $texto));
				if ($resdb->res == 0) {
					$resdb = $this->clientdb->buscarconsignatario(array('consignatario'=> $texto));
				} else {
					$res->error = 'Existe un error con el consignatario, favor vuelva a intentarlo';
					break;
				}
			}
			$consignatarioid = $resdb->matriz[0]->columnas[0];
			$resdb = $this->clientdb->insertarmanifiesto(array(
			                                             'itemid'=> $itemid,
			                                             'agenciaoperadorid'=> $agenciaoperadorid,
			                                             'naveid'=> $naveid,
			                                             'viaje'=> strtoupper($resexcel[$i][5]),
			                                             'nrmfto'=> $resexcel[$i][6],
			                                             'tipotransito'=> strtoupper($resexcel[$i][8]),
			                                             'contenedorid'=> $contenedorid,
			                                             'bl'=> strtoupper($resexcel[$i][13]),
			                                             'puertoembarqueid'=> $puertoembarqueid,
			                                             'purtodestinoid'=> $puertodestinoid,
			                                             'puertodescargaid'=> $puertodescargaid,
			                                             'mercanciaid'=> $mercanciaid,
			                                             'bruto'=> $resexcel[$i][20],
			                                             'neto'=> $resexcel[$i][19],
			                                             'servicioid'=> $servicioid,
			                                             'imoid'=> $imoid,
			                                             'sellos'=> strtoupper($resexcel[$i][23]),
			                                             'bultos'=> $resexcel[$i][24],
			                                             'consignatarioid'=> $consignatarioid,
			                                             'estadorecepcion'=> strtoupper($resexcel[$i][27]),
			                                             'periodo'=> strtoupper($resexcel[$i][28]),
			                                             'fecha'=> $fecha));
			if ($resdb->res == 0) {
				$res->error = 'OK';
				$res->fecha = $fecha;
			} else {
				$res->error = 'Existe un error con el cargado del manifiesto, favor vuelva a intentarlo';
				break;
			}
		}
		unlink($input->documento);
		return $res;
	}

	/**
	* Esta funcion busca los datos recien subidos para mostrarlos.
	*
	* @param detetime $input->fecha fecha en la que se subio el manifiesto
	* @return string $mostrarreciensubidosalidas->error OK si no ocurrio ningun error
	*		  mafiestomaritimo $mostrarreciensubidosalidas->manifiestomaritimo que contiene el manifiesto recien
	*             subido
	*/
	public function mostrarreciensubido($input) {
		$res = new mostrarreciensubidosalidas();
		$resdb = $this->clientdb->buscarmanifiestoxfecha(array('fecha'=> $input->fecha));
		if ($resdb->error == 0) {
			$res->error = 'OK';
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) { 
				$res->manifiestomaritimo[$i]->item = $resdb->matriz[$i]->columnas[0];
				$res->manifiestomaritimo[$i]->agencia = $resdb->matriz[$i]->columnas[1];
				$res->manifiestomaritimo[$i]->nave = $resdb->matriz[$i]->columnas[2];
				$res->manifiestomaritimo[$i]->viaje = $resdb->matriz[$i]->columnas[3];
				$res->manifiestomaritimo[$i]->nromfto = $resdb->matriz[$i]->columnas[4];
				$res->manifiestomaritimo[$i]->tipotransito = $resdb->matriz[$i]->columnas[5];
				$res->manifiestomaritimo[$i]->contenedor = $resdb->matriz[$i]->columnas[6];
				$res->manifiestomaritimo[$i]->tipocontenedor = $resdb->matriz[$i]->columnas[7];
				$res->manifiestomaritimo[$i]->operador = $resdb->matriz[$i]->columnas[8];
				$res->manifiestomaritimo[$i]->bl = $resdb->matriz[$i]->columnas[9];
				$res->manifiestomaritimo[$i]->puertoembarque = $resdb->matriz[$i]->columnas[10];
				$res->manifiestomaritimo[$i]->puertodescarga = $resdb->matriz[$i]->columnas[11];
				$res->manifiestomaritimo[$i]->puertodestino = $resdb->matriz[$i]->columnas[12];
				$res->manifiestomaritimo[$i]->mercancia = $resdb->matriz[$i]->columnas[13];
				$res->manifiestomaritimo[$i]->tara = $resdb->matriz[$i]->columnas[14];
				$res->manifiestomaritimo[$i]->neto = $resdb->matriz[$i]->columnas[15];
				$res->manifiestomaritimo[$i]->bruto = $resdb->matriz[$i]->columnas[16];
				$res->manifiestomaritimo[$i]->servicio = $resdb->matriz[$i]->columnas[17];
				$res->manifiestomaritimo[$i]->imo = $resdb->matriz[$i]->columnas[18];
				$res->manifiestomaritimo[$i]->sellos = $resdb->matriz[$i]->columnas[19];
				$res->manifiestomaritimo[$i]->bultos = $resdb->matriz[$i]->columnas[20];
				$res->manifiestomaritimo[$i]->consignatario = $resdb->matriz[$i]->columnas[21];
				$res->manifiestomaritimo[$i]->estado = $resdb->matriz[$i]->columnas[22];
				$res->manifiestomaritimo[$i]->periodo = $resdb->matriz[$i]->columnas[23];
			}
		} else {
			$res->error = 'Hubo un error al mostrar los datos recien subidos, favor vuelva a intentarlo';
		}
		return $res;
	}

	/**
	* Esta funcion genera el pdf del manifiesto recien subido por asociado y lo envia.
	*
	* @param detetime $input->fecha fecha en la que se subio el manifiesto
	* @return string $mostrarreciensubidosalidas->error OK si no ocurrio ningun error
	*/
	public function generarpdfyenviar($input) {
		$res = new generarpdfyenviarsalidas();
		$resdb = $this->clientdb->buscarmanifiestoxfecha(array('fecha'=> $input->fecha));
		if ($resdb->error == 0) {
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) { 
				$arrayaux[$i] = $resdb->matriz[$i]->columnas[21];
			}
			$tamanho = sizeof($arrayaux);
			$arrayaux = array_unique($arrayaux, SORT_LOCALE_STRING);
			for ($i=0; $i < $tamanho; $i++) {
				if (isset($arrayaux[$i])) {
					$resdb = $this->clientdb->buscarasociadoxconsignatario(array('consignatario' => $arrayaux[$i]));
					if ($resdb->error == 0 && isset($resdb->matriz[0]->columnas[3]) && $resdb->matriz[0]->columnas[3] != '') {
						$consignatarioid = $resdb->matriz[0]->columnas[0];
						$mailprincipal = $resdb->matriz[0]->columnas[3];
						$mailssecundarios = $resdb->matriz[0]->columnas[4];
						$rotulo = $resdb->matriz[0]->columnas[2];
						$resdb0 = $this->clientdb->buscarmanifiestodeconsignatario(array(
						                                                          'consignatarioid' => $consignatarioid,
						                                                          'fecha' => $input->fecha));
						if ($resdb0->error == 0) {
							$html = '<html><head><title></title></head><body><div>'.
							'<img src="../tmp/CNI.jpg" style="float:left;"><h1 align="center">'.
							'CAMARA NACIONAL DE INDUSTRIAS</h1></div><p><H5>'.
							'<DIV ALIGN=right><br>La Paz, '.date('d/m/Y').'</DIV><br><br>Se&ntilde;ores:<br>'.$rotulo.
							'<br>Presente.-<br><br>Mediante la presente, nos es grato comunicarle que su carga arrib&oacute;'.
							' al puerto de "Arica" con el siguente detalle:<br><br></H5></p>';
							if (sizeof($resdb0->matriz) == 1) {
								$html .= '<table class="bpmTopnTailC"><tbody><tr class="oddrow"><th>NAVE:</th><td>'.
								$resdb0->matriz[0]->columnas[0].'</td><th>NRO. MANIFIESTO:'.
								'</th><td>'.$resdb0->matriz[0]->columnas[1].'</td></tr><tr class="evenrow">'.
								'<th>TIPO TRANSITO:</th><td>'.$resdb0->matriz[0]->columnas[7].'</td>'.
								'<th>OPERADOR:</th><td>'.$resdb0->matriz[0]->columnas[8].
								'</td></tr><tr class="oddrow"><th>PUERTO DESEMBARQUE:</th><td>'.
								$resdb0->matriz[0]->columnas[12].'</td><th>MARCA CONTENEDOR:</th><td>'.
								$resdb0->matriz[0]->columnas[9].'</td></tr><tr class="evenrow"><th>TIPO CONTENEDOR:</th><td>'.
								$resdb0->matriz[0]->columnas[2].'</td><th>MERCANCIA:</th><td>'.
								$resdb0->matriz[0]->columnas[3].'</td></tr><tr class="oddrow"><th>TARA:</th><td>'.
								$resdb0->matriz[0]->columnas[4].'</td><th>NETO:</th><td>'.
								$resdb0->matriz[0]->columnas[5].'</td></tr><tr class="evenrow"><th>BRUTO:</th><td>'.
								$resdb0->matriz[0]->columnas[6].'</td><th>BL:</th><td>'.
								$resdb0->matriz[0]->columnas[10].'</td></tr><tr class="oddrow"><th>PUERTO ORIGEN:</th><td>'.
								$resdb0->matriz[0]->columnas[11].'</td><th>DESTINO BOLIVIA:</th><td>'.
								$resdb0->matriz[0]->columnas[13].'</td></tr><tr class="evenrow"><th>SERVICIO:</th><td>'.
								$resdb0->matriz[0]->columnas[14].'</td><th>SELLOS:</th><td>'.
								$resdb0->matriz[0]->columnas[15].'</td></tr><tr class="oddrow"><th>IMO:</th><td>'.
								$resdb0->matriz[0]->columnas[16].'</td><th>BULTOS:</th><td>'.
								$resdb0->matriz[0]->columnas[17].'</td>';
							} else {
								for ($j = 0; $j < sizeof($resdb0->matriz); $j++) { 
									$arrayauxpuerto[$j] = $resdb0->matriz[$j]->columnas[11];
								}
								$arrayauxpuerto = array_unique($arrayauxpuerto, SORT_LOCALE_STRING);
								$html .= '<table class="bpmTopnTailC"><tbody><tr class="oddrow"><th>NAVE:</th><td>'.
								$resdb0->matriz[0]->columnas[0].'</td></tr><tr class="evenrow"><th>NRO. MANIFIESTO:</th><td>'.
								$resdb0->matriz[0]->columnas[1].'</td></tr><tr class="oddrow"><th>TIPO TRANSITO:</th><td>'.
								$resdb0->matriz[0]->columnas[7].'</td></tr><tr class="evenrow"><th>OPERADOR:</th><td>'.
								$resdb0->matriz[0]->columnas[8].'</td></tr><tr class="oddrow"><th>PUERTO DESEMBARQUE:</th>'.
								'<td>'.$resdb0->matriz[0]->columnas[12].'</td></tr>';
								if (sizeof($arrayauxpuerto) == 1) {
									$html .= '<tr class="evenrow"><th>PUERTO ORIGEN:</th><td>'.
									$resdb0->matriz[0]->columnas[11].'</td></tr>';
								}
								$html .= '</tbody></table><br>'.
								'<table class="bpmTopnTailC"><thead><tr class="headerrow"><th>MARCA CONTENEDOR</th>'.
								'<th>TIPO CONTENEDOR</th><th>MERCANCIA</th><th>TARA</th><th>NETO</th><th>BRUTO</th>'.
								'<th>BL</th>';
								if (sizeof($arrayauxpuerto) > 1) {
									$html .= '<th>PUERTO ORIGEN</th>';
								}
								$html .= '<th>DESTINO BOLIVIA</th><th>SERVICIO</th><th>SELLOS</th>'.
								'<th>IMO</th><th>BULTOS</th></tr></thead><tbody>';
								$rowclass = 'oddrow';
								for ($j = 0; $j < sizeof($resdb0->matriz); $j++) { 
									$html .= '<tr class="'.$rowclass.'"><td>'.$resdb0->matriz[$j]->columnas[9].'</td><td>'.
									$resdb0->matriz[$j]->columnas[2].'</td><td>'.$resdb0->matriz[$j]->columnas[3].
									'</td><td>'.$resdb0->matriz[$j]->columnas[4].'</td><td>'.$resdb0->matriz[$j]->columnas[5].
									'</td><td>'.$resdb0->matriz[$j]->columnas[6].'</td><td>'.$resdb0->matriz[$j]->columnas[10].
									'</td>';
									if (sizeof($arrayauxpuerto) > 1) {
										$html .= '<td>'.$resdb0->matriz[$j]->columnas[11].'</td>';
									}
									$html .= '<td>'.$resdb0->matriz[$j]->columnas[13].
									'</td><td>'.$resdb0->matriz[$j]->columnas[14].'</td><td>'.
									$resdb0->matriz[$j]->columnas[15].'</td><td>'.$resdb0->matriz[$j]->columnas[16].
									'</td><td>'.$resdb0->matriz[$j]->columnas[17].'</td></tr>';
									if (($j + 1) % 2 == 0) {
										$rowclass = 'oddrow';
									} else {
										$rowclass = 'evenrow';
									}
								}
							}
							$html .= '</tbody></table><br><p color="red">*Fuente: Terminal Puerto Arica - TPA</p></body></html>';
							$pdf = crearsimple($html, $rotulo.date('d-m-Y'));
							$mensaje = 'Su reporte de carga arrivada a Arica, esta listo.';
							$asunto = 'PARTE DE RECEPCION MARITIMA';
							if ($mailssecundarios != '') {
								$mails =  explode(',', $mailssecundarios);
							}
							$enviarpdf = enviarpdf($mailprincipal, $mails, $mensaje, $asunto, $pdf);
							if ($enviarpdf == 1) {
								$res->error = 'Hubo un error al intentar enviar el mail de parte de recpecion maritima'.
								', favor vuelva a intentarlo';
								break;
							} else {
								$res->error = 'OK';
								unlink($pdf);
							}
						} else {
							$res->error = 'Hubo un error al tratar de armar el pdf, favor vuelva a intentarlo';
							break;
						}
				 	}
				}
			}
		} else {
			$res->error = 'Hubo un error al intentar buscar datos para generar pdf, favor vuelva a intentarlo';
		}
		return $res;
	}
}
$server = new SoapServer($asdir."/wsdl/manifiesto.wsdl");
$server->setClass("manifiesto");
$server->handle();

