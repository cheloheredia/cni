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
class dab {

	/**
	* Esta funcion inicia todos los clientes necesarios para el funcionamiento de la clase dab.
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
	* Esta funcion el cambio y casteo del formato de fecha excel a formato fecha regurlar.
	*
	* @param string $fecha la fecha que hay que castear
	* @return string $fecha nueva fecha
	*/
	public function castearFechaExcel($fecha) {
		if ($fecha == '01/01/0001' || $fecha == 1) {
			$fecha = '';
		} else {
			$fecha = gmdate("Y-m-d",($fecha-25569)*86400);
		}
		return $fecha;
	}

	/**
	* Esta funcion realiza la subida de los datos del manifiesto a la base de datos.
	*
	* @param string $input=>documento direccion donde se encuentra el documento excel subido
	* @return string $subirsalidas->error OK si no ocurrio ningun error
	*         datetime $subirsalidas->fecha fecha en la que se subio el manifiesto
	*/
	public function mostrarrecintos($input) {
		$res = new mostrarrecintossalidas();
		$resdb = $this->clientdb->buscarrecintosac(array('recinto' => $input->recinto));
		if ($resdb->res == 0) {
			$res->error = 'OK';
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) { 
				$res->recinto[$i]->id = $resdb->matriz[$i]->columnas[0];
				$res->recinto[$i]->recinto = $resdb->matriz[$i]->columnas[1];
			}
		} else {
			$res->error = 'Existe un error con recintos, favor vuelva a intentarlo';
		}
		return $res;
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
		$resdb = $this->clientdb->buscarrecintosac(array('recinto'=> $input->recinto));
		if ($resdb->error == 0) {
			$recintoid = $resdb->matriz[0]->columnas[0];
			$resexcel = leeExcel($input->documento);
			$aux = 1;
			for ($i = 1; $i < sizeof($resexcel); $i++) {
				$texto = strtoupper($resexcel[$i][9]);
				$resdb = $this->clientdb->buscarconsignatario(array('consignatario'=> $texto));
				if ($resdb->error == 1) {
					$aux++;
					continue;
				}
				$consignatarioid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][20]);
				$resdb = $this->clientdb->buscartmercanciadab(array('tipo'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertartmercanciadab(array('tipo'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscartmercanciadab(array('tipo'=> $texto));
					} else {
						$res->error = 'Existe un error con el tipo de mercancia, favor vuelva a intentarlo';
						break;
					}
				}
				$tmercanciaid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][16]);
				$resdb = $this->clientdb->buscarmercanciadab(array('tipo'=> $tmercanciaid, 'mercancia' => $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertarmercaciadab(array('tipo'=> $tmercanciaid, 'mercancia' => $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscarmercanciadab(array('tipo'=> $tmercanciaid,
						                                             'mercancia' => $texto));
					} else {
						$res->error = 'Existe un error con la mercancia, favor vuelva a intentarlo';
						break;
					}
				}
				$mercanciaid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][17]);
				$resdb = $this->clientdb->buscaralmacendab(array('recinto'=> $recintoid, 'almacen' => $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertaralmacendab(array('recinto'=> $recintoid, 'almacen' => $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscaralmacendab(array('recinto'=> $recintoid, 'almacen' => $texto));
					} else {
						$res->error = 'Existe un error con el almacen, favor vuelva a intentarlo';
						break;
					}
				}
				$almacenid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][18]);
				$resdb = $this->clientdb->buscartdeposito(array('tipo'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertartdeposito(array('tipo'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscartdeposito(array('tipo'=> $texto));
					} else {
						$res->error = 'Existe un error con el tipo de deposito, favor vuelva a intentarlo';
						break;
					}
				}
				$tdepositoid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][23]);
				$resdb = $this->clientdb->buscarestadodab(array('estado'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertarestadodab(array('estado'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscarestadodab(array('estado'=> $texto));
					} else {
						$res->error = 'Existe un error con el estado, favor vuelva a intentarlo';
						break;
					}
				}
				$estadoid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][26]);
				$resdb = $this->clientdb->buscarcamiondab(array('camion'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertarcamiondab(array('camion'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscarcamiondab(array('camion'=> $texto));
					} else {
						$res->error = 'Existe un error con el camion, favor vuelva a intentarlo';
						break;
					}
				}
				$camionid = $resdb->matriz[0]->columnas[0];
				$texto = strtoupper($resexcel[$i][0]);
				$resdb = $this->clientdb->buscarreportedab(array('viaje'=> $texto));
				if ($resdb->error == 0) {
					$dabid = $resdb->matriz[0]->columnas[0];
					$resdb = $this->clientdb->actualizarreportedab(array(
					                                               'ingreso'=> $resexcel[$i][1],
					                                               'enbarque'=> strtoupper($resexcel[$i][3]),
					                                               'item'=> $resexcel[$i][4],
					                                               'fechaingreso'=> $this->castearFechaExcel($resexcel[$i][5]),
					                                               'fechabalanza'=> $this->castearFechaExcel($resexcel[$i][6]),
					                                               'fecharecepcion'=> $this->castearFechaExcel($resexcel[$i][7]),
					                                               'fechasalida'=> $this->castearFechaExcel($resexcel[$i][8]),
					                                               'consignatarioid'=> $consignatarioid,
					                                               'bultosmanifiesto'=> $resexcel[$i][10],
					                                               'pesomanifestado'=> $resexcel[$i][11],
					                                               'bultosrecevidos'=> $resexcel[$i][12],
					                                               'pesorecivido'=> $resexcel[$i][13],
					                                               'saldopeso'=> $resexcel[$i][15],
					                                               'saldobultos'=> $resexcel[$i][14],
					                                               'descripcionid'=> $mercanciaid,
					                                               'almacenid'=> $almacenid,
					                                               'registrodepositoid'=> $tdepositoid,
					                                               'fechavencimiento'=> $this->castearFechaExcel($resexcel[$i][22]),
					                                               'estadoid'=> $estadoid,
					                                               'dvi'=> $resexcel[$i][25],
					                                               'camionid'=> $camionid,
					                                               'chasis'=> $resexcel[$i][27],
					                                               'fecha' => $fecha,
					                                               'id'=> $dabid
					                                               ));
					if ($resdb->res == 0) {
						$res->error = 'OK';
					} else {
						$res->error = 'Existe un error con la actualizacion DAB, favor vuelva a intentarlo';
						break;
					}
				} else {
					$resdb = $this->clientdb->insertarreportedab(array(
					                                             'viaje'=> $texto,
					                                             'ingreso'=> $resexcel[$i][1],
					                                             'enbarque'=> strtoupper($resexcel[$i][3]),
					                                             'item'=> $resexcel[$i][4],
					                                             'fechaingreso'=> $this->castearFechaExcel($resexcel[$i][5]),
					                                             'fechabalanza'=> $this->castearFechaExcel($resexcel[$i][6]),
					                                             'fecharecepcion'=> $this->castearFechaExcel($resexcel[$i][7]),
					                                             'fechasalida'=> $this->castearFechaExcel($resexcel[$i][8]),
					                                             'consignatarioid'=> $consignatarioid,
					                                             'bultosmanifiesto'=> $resexcel[$i][10],
					                                             'pesomanifestado'=> $resexcel[$i][11],
					                                             'bultosrecevidos'=> $resexcel[$i][12],
					                                             'pesorecivido'=> $resexcel[$i][13],
					                                             'saldopeso'=> $resexcel[$i][15],
					                                             'saldobultos'=> $resexcel[$i][14],
					                                             'descripcionid'=> $mercanciaid,
					                                             'almacenid'=> $almacenid,
					                                             'registrodepositoid'=> $tdepositoid,
					                                             'fechavencimiento'=> $this->castearFechaExcel($resexcel[$i][22]),
					                                             'estadoid'=> $estadoid,
					                                             'dvi'=> $resexcel[$i][25],
					                                             'camionid'=> $camionid,
					                                             'chasis'=> $resexcel[$i][27],
					                                             'fecha'=> $fecha
					                                             ));
					if ($resdb->res == 0) {
						$res->error = 'OK';
					} else {
						$res->error = 'Existe un error con la insersion DAB, favor vuelva a intentarlo';
						break;
					}
				}
			}
		} else {
			$res->error = 'El recinto no existe, favor vuelva a intentarlo';
		}
		if ($aux == sizeof($resexcel)) {
			$res->error = 'No fue cargado el archivo ya que sus consignatarios no fueron presentados anteriormente';
		}
		if ($aux < sizeof($resexcel) && $aux > 1) {
			$res->error = 'OK';
			$res->fecha = $fecha;
		}
		if ($res->error == 'OK') {
			$res->fecha = $fecha;
		}
		unlink($input->documento);
		return $res;
	}

	/**
	* Esta funcion busca los datos recien subidos para mostrarlos.
	*
	* @param detetime $input->fecha fecha en la que se subio el manifiesto
	* @return string $mostrardabreciensubidosalidas->error OK si no ocurrio ningun error
	*		  mafiestomaritimo $mostrardabreciensubidosalidas->dab que contiene el manifiesto recien
	*             subido
	*/
	public function mostrardabreciensubido($input) {
		$res = new mostrardabreciensubidosalidas();
		$resdb = $this->clientdb->buscardabfecha(array('fecha'=> $input->fecha));
		if ($resdb->error == 0) {
			$res->error = 'OK';
			$res->dab->recinto = $resdb->matriz[0]->columnas[24];
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) { 
				$res->dab->reporte[$i]->viaje = $resdb->matriz[$i]->columnas[1];
				$res->dab->reporte[$i]->nroingreso = $resdb->matriz[$i]->columnas[2];
				$res->dab->reporte[$i]->item = $resdb->matriz[$i]->columnas[3];
				$res->dab->reporte[$i]->fechaingreso = $resdb->matriz[$i]->columnas[4];
				$res->dab->reporte[$i]->fechabalanza = $resdb->matriz[$i]->columnas[5];
				$res->dab->reporte[$i]->fecharecepcion = $resdb->matriz[$i]->columnas[6];
				$res->dab->reporte[$i]->fechasalida = $resdb->matriz[$i]->columnas[7];
				$res->dab->reporte[$i]->consignatario = $resdb->matriz[$i]->columnas[8];
				$res->dab->reporte[$i]->bultosman = $resdb->matriz[$i]->columnas[9];
				$res->dab->reporte[$i]->pesoman = $resdb->matriz[$i]->columnas[10];
				$res->dab->reporte[$i]->bultorec = $resdb->matriz[$i]->columnas[11];
				$res->dab->reporte[$i]->pesorec = $resdb->matriz[$i]->columnas[12];
				$res->dab->reporte[$i]->saldopeso = $resdb->matriz[$i]->columnas[13];
				$res->dab->reporte[$i]->saldobulto = $resdb->matriz[$i]->columnas[14];
				$res->dab->reporte[$i]->mercancia = $resdb->matriz[$i]->columnas[15];
				$res->dab->reporte[$i]->almacen = $resdb->matriz[$i]->columnas[16];
				$res->dab->reporte[$i]->deposito = $resdb->matriz[$i]->columnas[17];
				$res->dab->reporte[$i]->tmercancia = $resdb->matriz[$i]->columnas[18];
				$res->dab->reporte[$i]->fechavenc = $resdb->matriz[$i]->columnas[19];
				$res->dab->reporte[$i]->estado = $resdb->matriz[$i]->columnas[20];
				$res->dab->reporte[$i]->dui = $resdb->matriz[$i]->columnas[21];
				$res->dab->reporte[$i]->camion = $resdb->matriz[$i]->columnas[22];
				$res->dab->reporte[$i]->chasis = $resdb->matriz[$i]->columnas[23];
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
		$resdb = $this->clientdb->buscardabfecha(array('fecha'=> $input->fecha));
		if ($resdb->error == 0) {
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) { 
				$arrayaux[$i] = $resdb->matriz[$i]->columnas[8];
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
						$resdb0 = $this->clientdb->buscardabconsiganatario(array(
						                                                          'consignatarioid' => $consignatarioid,
						                                                          'fecha' => $input->fecha));
						if ($resdb0->error == 0) {
							$html = '<html><head><title></title></head><body><div>'.
							'<img src="../tmp/CNI.jpg" style="float:left;"><h1 align="center">'.
							'CAMARA NACIONAL DE INDUSTRIAS</h1></div><p><H5>'.
							'<DIV ALIGN=right><br>La Paz, '.date('d/m/Y').'</DIV><br><br>Se&ntilde;ores:<br>'.$rotulo.
							'<br>Presente.-<br><br>Mediante la presente, nos es grato comunicarle estado de su carga'.
							' en el recinto de '.$resdb0->matriz[0]->columnas[24].' DAB con el siguente detalle:<br><br></H5></p>';
							if (sizeof($resdb0->matriz) == 1) {
								$html .= '<table class="bpmTopnTailC"><tbody><tr class="oddrow"><th>VIAJE:</th><td>'.
								$resdb0->matriz[0]->columnas[1].'</td><th>INGRESO:'.
								'</th><td>'.$resdb0->matriz[0]->columnas[2].'</td></tr><tr class="evenrow">'.
								'<th>ITEM:</th><td>'.$resdb0->matriz[0]->columnas[3].'</td>'.
								'<th>FECHA INGRESO:</th><td>'.$resdb0->matriz[0]->columnas[4].
								'</td></tr><tr class="oddrow"><th>FECHA BALANZA:</th><td>'.
								$resdb0->matriz[0]->columnas[5].'</td><th>FECHA PARTE RECEPCION:</th><td>'.
								$resdb0->matriz[0]->columnas[6].'</td></tr><tr class="evenrow"><th>FECHA SALIDA:</th><td>'.
								$resdb0->matriz[0]->columnas[7].'</td><th>BULTOS MANIFESTADOS:</th><td>'.
								$resdb0->matriz[0]->columnas[9].'</td></tr><tr class="oddrow"><th>PESO MANIFESTADO:</th><td>'.
								$resdb0->matriz[0]->columnas[10].'</td><th>BULTOS RECIVIDOS:</th><td>'.
								$resdb0->matriz[0]->columnas[11].'</td></tr><tr class="evenrow"><th>PESO RECIVIDO:</th><td>'.
								$resdb0->matriz[0]->columnas[12].'</td><th>SALDO BULTOS:</th><td>'.
								$resdb0->matriz[0]->columnas[13].'</td></tr><tr class="oddrow"><th>SALDO PESO:</th><td>'.
								$resdb0->matriz[0]->columnas[14].'</td><th>DESCRIPCION:</th><td>'.
								$resdb0->matriz[0]->columnas[15].'</td></tr><tr class="evenrow"><th>ALMACEN:</th><td>'.
								$resdb0->matriz[0]->columnas[16].'</td><th>REG. DEPOSITO:</th><td>'.
								$resdb0->matriz[0]->columnas[17].'</td></tr><tr class="oddrow"><th>TIPO MERCANCIA:</th><td>'.
								$resdb0->matriz[0]->columnas[18].'</td><th>FECHA VENCIMIENTO:</th><td>'.
								$resdb0->matriz[0]->columnas[19].'</td></tr><tr class="evenrow"><th>ESTADO:</th><td>'.
								$resdb0->matriz[0]->columnas[20].'</td><th>DUI:</th><td>'.
								$resdb0->matriz[0]->columnas[21].'</td></tr><tr class="oddrow"><th>CAMION:</th><td>'.
								$resdb0->matriz[0]->columnas[22].'</td><th>CHASIS:</th><td>'.
								$resdb0->matriz[0]->columnas[23].'</td></tr>';
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
							$html .= '</tbody></table><br><p color="red">*Fuente: Depositos Aduaneros Bolivianos - DAB</p></body></html>';
							$pdf = crearsimple($html, $rotulo.date('d-m-Y'));
							$mensaje = 'Su reporte de carga DAB, esta listo.';
							$asunto = 'REPORTE DAB';
							if ($mailssecundarios != '') {
								$mails =  explode(',', $mailssecundarios);
							}
							$enviarpdf = enviarpdf($mailprincipal, $mails, $mensaje, $asunto, $pdf);
							if ($enviarpdf == 1) {
								$res->error = 'Hubo un error al intentar enviar el mail de reporte DAB'.
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
$server = new SoapServer($asdir."/wsdl/dab.wsdl");
$server->setClass("dab");
$server->handle();

