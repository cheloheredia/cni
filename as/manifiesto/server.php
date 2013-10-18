<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
//class definitions
require_once('wsdl.php');
require_once('../client/wsdldb.php');
//SERVER
class manifiesto {
	public function __construct(){
		$this->clientdb = new SoapClient('http://127.0.0.1:12/wsdl/db.wsdl',
		                                 array('trace' => 1,
		                                       'cache_wsdl' => WSDL_CACHE_NONE,
		                                       'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
		                                       'classmap' => $GLOBALS['classMapdb']));
		include '../client/excelPHP.php';
	}

	public function quitar_tildes($cadena) {
		$no_permitidas = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬",
		                        "Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯",
		                        "Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas = array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u",
		                     "c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

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
}
$server = new SoapServer("http://127.0.0.1:14/wsdl/manifiesto.wsdl");
$server->setClass("manifiesto");
$server->handle();
