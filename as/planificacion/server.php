<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
require_once('wsdl.php');
require_once('../client/wsdldb.php');
class planificacion {

	/**
	* Esta funcion inicia todos los clientes necesarios para el funcionamiento de la clase planificacion.
	*/
	public function __construct(){
		$this->clientdb = new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',
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
	* Esta funcion la comparacion de dos cadenas como like de sql.
	*
	* @param string $regex exprecion regular base para comparar
	* @param string $palabra cadena con la que se comparara a la exprecion regular
	* @return int 0 si la palabra esta en la exprecion regular
	*/
	public function comparar($regex, $palabra) {
		if (preg_match($regex, $palabra)) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	* Esta funcion realiza la subida de los datos de la planificacion a la base de datos.
	*
	* @param string $input=>documento direccion donde se encuentra el documento excel subido
	* @return string $subirsalidas->error OK si no ocurrio ningun error
	*         datetime $subirsalidas->fecha fecha en la que se subio la planificacion
	*/
	public function subir($input) {
		$res = new subirsalidas();
		$fecha = date("Y-m-d H:i:s");
		$resexcel = leeExcel($input->documento);
		$aux = 0;
		for ($i = 0; $i < sizeof($resexcel); $i++) {
			if ($this->comparar("/Turno$/", $resexcel[$i][11]) == 0) {
				$texto = strtoupper($resexcel[$i][11]);
				$resdb = $this->clientdb->buscarturno(array('turno'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertarturno(array('turno'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscarturno(array('turno'=> $texto));
					} else {
						$res->error = 'Existe un error con el turno, favor vuelva a intentarlo';
						break;
					}
				}
				$turnoid = $resdb->matriz[0]->columnas[0];
			}
			if ($resexcel[$i][11] == 'Recuento') {
				$tablas[$aux]->almacen = $resexcel[$i][1];
			}
			if ($resexcel[$i][1] == 'Contenedor') {
				$tablas[$aux]->inicio = $i + 1;
			}
			if ($resexcel[$i][1] == 'Ctrs') {
				$tablas[$aux]->fin = $i - 1;
				$aux++;
			}
		}
		if (isset($tablas)) {
			for ($i = 0; $i < $aux -1; $i++) {
				$texto = strtoupper($tablas[$i]->almacen);
				$resdb = $this->clientdb->buscaralmacen(array('almacen'=> $texto));
				if ($resdb->error == 1) {
					$resdb = $this->clientdb->insertaralmacen(array('almacen'=> $texto));
					if ($resdb->res == 0) {
						$resdb = $this->clientdb->buscaralmacen(array('almacen'=> $texto));
					} else {
						$res->error = 'Existe un error con el almacen, favor vuelva a intentarlo';
						break;
					}
				}
				$almacenid = $resdb->matriz[0]->columnas[0];
				for ($j = $tablas[$i]->inicio; $j < $tablas[$i]->fin; $j++) { 
					$auxfila = 0;
					for ($k = 1; $k < sizeof($resexcel[$j]); $k++) { 
						if ($resexcel[$j][$k] == '') {
							$auxfila++;
						}
					}
					if ($auxfila < 12) {
						$texto = strtoupper($resexcel[$j][2]);
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
						$tipocontenedorid = $resdb->matriz[0]->columnas[0];
						$texto = $resexcel[$j][1];
						$resdb = $this->clientdb->buscarcontenedor(array('contenedor'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertarcontenedor(array(
							                                             'contenedor'=> $texto,
							                                             'tipoid'=>$tipocontenedorid,
							                                             'peso'=>0));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscarcontenedor(array('contenedor'=> $texto));
							} else {
								$res->error = 'Existe un error con el contenedor, favor vuelva a intentarlo';
								break;
							}
						}
						$contenedorid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][11]);
						$resdb = $this->clientdb->buscartransportista(array('transportista'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertartransportista(array('transportista'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscartransportista(array('transportista'=> $texto));
							} else {
								$res->error = 'Existe un error con el transportista, favor vuelva a intentarlo';
								break;
							}
						}
						$transportistaid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][3]);
						$resdb = $this->clientdb->buscarcamion(array(
						                                       'transportista'=> $transportistaid,
						                                       'camion'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertarcamion(array(
							                                         'transportista'=> $transportistaid,
							                                         'camion'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscarcamion(array(
								                                       'transportista'=> $transportistaid,
								                                       'camion'=> $texto));
							} else {
								$res->error = 'Existe un error con el camion, favor vuelva a intentarlo';
								break;
							}
						}
						$camionid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][8]);
						$resdb = $this->clientdb->buscartipobulto(array('tipo'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertartipobulto(array('tipo'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscartipobulto(array('tipo'=> $texto));
							} else {
								$res->error = 'Existe un error con el tipo de bulto, favor vuelva a intentarlo';
								break;
							}
						}
						$tbultoid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][7]);
						$resdb = $this->clientdb->buscarbulto(array(
						                                      'tipo'=> $tbultoid,
						                                      'bulto'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertarbutlo(array(
							                                        'tipo'=> $tbultoid,
							                                        'bulto'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscarbulto(array(
								                                      'tipo'=> $tbultoid,
								                                      'bulto'=> $texto));
							} else {
								$res->error = 'Existe un error con el bulto, favor vuelva a intentarlo';
								break;
							}
						}
						$bultoid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][9]);
						$resdb = $this->clientdb->buscarlugar(array(
						                                      'almacen'=> $almacenid,
						                                      'lugar'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertarlugar(array(
							                                        'almacen'=> $almacenid,
							                                        'lugar'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscarlugar(array(
								                                      'almacen'=> $almacenid,
								                                      'lugar'=> $texto));
							} else {
								$res->error = 'Existe un error con el lugar, favor vuelva a intentarlo';
								break;
							}
						}
						$lugarid = $resdb->matriz[0]->columnas[0];
						$texto = strtoupper($resexcel[$j][12]);
						$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
						if ($resdb->error == 1) {
							$resdb = $this->clientdb->insertarpuerto(array('puerto'=> $texto));
							if ($resdb->res == 0) {
								$resdb = $this->clientdb->buscarpuerto(array('puerto'=> $texto));
							} else {
								$res->error = 'Existe un error con el destino, favor vuelva a intentarlo';
								break;
							}
						}
						$destinoid = $resdb->matriz[0]->columnas[0];
						$resdb = $this->clientdb->insertarplanificacion(array(
						                                             'turno'=> $turnoid,
						                                             'lugar'=> $lugarid,
						                                             'contenedor'=> $contenedorid,
						                                             'camion'=> $camionid,
						                                             'matriz'=> $resexcel[$j][4],
						                                             'cantidad'=> $resexcel[$j][5],
						                                             'peso'=> $resexcel[$j][6],
						                                             'bulto'=> $bultoid,
						                                             'baroti'=> $resexcel[$j][10],
						                                             'destino'=> $destinoid,
						                                             'fecha'=> $fecha));
						if ($resdb->res == 0) {
							$res->error = 'OK';
							$res->fecha = $fecha;
						} else {
							$res->error = 'Existe un error con el cargado de la planificacion, favor vuelva a intentarlo';
							break;
						}
					}
				}
			}
		}	
		unlink($input->documento);
		return $res;
	}

	/**
	* Esta funcion busca los datos recien subidos para mostrarlos.
	*
	* @param detetime $input->fecha fecha en la que se subio la planificacion
	* @return string $mostrarreciensubidosalidas->error OK si no ocurrio ningun error
	*		  turno $mostrarreciensubidosalidas->planificacion que contiene la planficacion recien subida
	*/
	public function mostrarreciensubido($input) {
		$res = new mostrarreciensubidosalidas();
		$resdb = $this->clientdb->buscarplanificacionxfecha(array('fecha'=> $input->fecha));
		if ($resdb->error == 0) {
			$res->error = 'OK';
			$turno = '';
			$turnoi = -1;
			$almacen = '';
			$almaceni = -1;
			$tablai = 0;
			for ($i = 0; $i < sizeof($resdb->matriz); $i++) {
				if ($resdb->matriz[$i]->columnas[0] != $turno) {
					$turnoi++;
					$turno = $resdb->matriz[$i]->columnas[0];
					$res->planificacion[$turnoi]->turno = $turno;
				}
				if ($resdb->matriz[$i]->columnas[0] == $turno && $resdb->matriz[$i]->columnas[1] != $almacen) {
					$almaceni++;
					$almacen = $resdb->matriz[$i]->columnas[1];
					$res->planificacion[$turnoi]->almacen[$almaceni]->almacen = $almacen;
					$tablai = 0;
				}
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->contenedor = $resdb->matriz[$i]->columnas[2];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->tipo = $resdb->matriz[$i]->columnas[3];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->placa = $resdb->matriz[$i]->columnas[4];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->matriz = $resdb->matriz[$i]->columnas[5];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->cantidad = $resdb->matriz[$i]->columnas[6];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->peso = $resdb->matriz[$i]->columnas[7];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->descripcion = $resdb->matriz[$i]->columnas[8];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->tipobulto = $resdb->matriz[$i]->columnas[9];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->lugar = $resdb->matriz[$i]->columnas[10];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->baroti = $resdb->matriz[$i]->columnas[11];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->transportista = $resdb->matriz[$i]->columnas[12];
				$res->planificacion[$turnoi]->almacen[$almaceni]->tabla[$tablai]->destino = $resdb->matriz[$i]->columnas[13];
				$tablai++;
			}
		} else {
			$res->error = 'Hubo un error al mostrar los datos recien subidos, favor vuelva a intentarlo';
		}
		return $res;
	}
}
$server = new SoapServer("http://127.0.0.1:14/wsdl/planificacion.wsdl");
$server->setClass("planificacion");
$server->handle();

