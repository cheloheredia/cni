<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
require_once('wsdl.php');
class db {

	/**
	 * @var sql conection
	 */
	public $conexion;
	
	/**
	* Esta funcion realiza la apertura de la instancia de conexion con el
	* servidor MySql.
	*
	* @return bool true si la conexion fue correcta
	*/
	public function conexion() {
		$this->conexion = mysql_connect('127.0.0.1:3306', 'cni', '2c0n1i3');
		mysql_select_db('cni', $this->conexion);
	}
	
	/**
	* Esta funcion sierra la conexxion con el servidor MySql.
	*
	* @return bool true si la desconexion fue correcta
	*/
	public function desconexion() {
		mysql_close($this->conexion);
	}
	
	/**
	* Esta funcion ejecuta cualquier consulta sql.
	*
	* @param string $query consulta sql
	* @return sqlresponse contiene la respuesta de la ejecuion de la consulta
	*/
	public function ejecutarquery($query) {
		mysql_real_escape_string($query);
		return(mysql_query($query));
	}
	
	/**
	* Esta funcion convierte un sqlresponse en una estructura de matriz.
	*
	* @param string $query consulta sql
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function mostrar($query) {
		$sqlmatriz = new resquery();
		$sql = $this->ejecutarquery($query);
		if (mysql_num_rows($sql) > 0) {
			for ($j = 0; $j < mysql_num_rows($sql); $j++) {
				$row = mysql_fetch_row($sql);
				for ($i = 0; $i < mysql_num_fields($sql); $i++) {
					$sqlmatriz->matriz[$j]->columnas[$i] = $row[$i];
				}
			}
			$sqlmatriz->error = 0;
			return $sqlmatriz;
		} else {
			$sqlmatriz->error = 1;
			return $sqlmatriz;
		}
	}
	
	/**
	* Esta funcion busca un item definido en la tabla item.
	*
	* @param string $input->item el item que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaritem($input) {
		$this->conexion();
		$response = $this->mostrar("select a.itn, a.ititem from item a where a.ititem='".$input->item."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un item en la tabla item.
	*
	* @param string $input->item el item que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaritem($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into item values('','".$input->item."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca una agencia definida en la tabla agencia.
	*
	* @param string $input->agencia la agencia que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaragencia($input) {
		$this->conexion();
		$response = $this->mostrar("select a.an, a.aagencia from agencia a where a.aagencia = '".$input->agencia."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta una agencia en la tabla agencia.
	*
	* @param string $input->agencia la agencia que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaragencia($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into agencia values('','".$input->agencia."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un operador en la tabla operador.
	*
	* @param string $input->operador el operdor que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaroperador($input) {
		$this->conexion();
		$response = $this->mostrar("select a.opn, a.opoperador from operador a where a.opoperador = '".
		                           $input->operador."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un operador en la tabla operador.
	*
	* @param string $input->operador el operador que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaroperador($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into operador values('','".$input->operador."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca una relacion entre agencia y operador en la tabla agenciaoperador.
	*
	* @param int $input->agenciaid la agencia que desea buscar
	* @param int $input->operadorid el operador que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaragenciaoperador($input) {
		$this->conexion();
		$response = $this->mostrar("select a.aon, a.aoagencia, a.aooperador from agenciaoperador a where a.aoagencia=".
		                           $input->agenciaid." and a.aooperador = ".$input->operadorid);
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta una relacion entre una agencia y un operador.
	*
	* @param int $input->agenciaid agencia de la relacion
	* @param int $input->operadorid operador de la relacion
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaragenciaoperador($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into agenciaoperador values('',".
		    $input->agenciaid.",".$input->operadorid.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca una nave en la tabla nave.
	*
	* @param string $input->nave la nave que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarnave($input) {
		$this->conexion();
		$response = $this->mostrar("select a.nn, a.nnave, b.opoperador from nave a, operador ".
		                           "b where a.noperador = b.opn and a.nnave = '".$input->nave."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta una nave en la tabla nave.
	*
	* @param string $input->nave la nave que se quiere insertar
	* @param int $input->operadorid operador de la nave
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarnave($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into nave values('','".$input->nave."',".$input->operadorid.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un tipo de contenedor en la tabla tipocontenedor.
	*
	* @param string $input->tipo el tipo de contenedor que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscartipocontenedor($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tcn, a.tctipo from tcontenedor a where tctipo = '".$input->tipo."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un tipo de contenedor.
	*
	* @param string $input->tipo el tipo
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertartipocontenedor($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into tcontenedor values('','".$input->tipo."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un contenedor en la tabla contenedor.
	*
	* @param string $input->contenedor el contenedor que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarcontenedor($input) {
		$this->conexion();
		$response = $this->mostrar("select a.con, a.cocontenedor, a.copeso, b.tctipo from contenedor a,".
		                           " tcontenedor b where a.cotipo = b.tcn and a.cocontenedor = '".
		                           $input->contenedor."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un contenedor en la tabla contenedor.
	*
	* @param string $input->contenedor contenedor para insertar
	* @param int $input->tipoid tipo del contenedor
	* @param float $input->peso peso del contenedor
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarcontenedor($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into contenedor values('','"
		    .$input->contenedor."',".$input->tipoid.",".$input->peso.")")){
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un puerto en la tabla puerto.
	*
	* @param string $input->puerto el puerto que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarpuerto($input) {
		$this->conexion();
		$response = $this->mostrar("select a.pn, a.ppuerto from puerto a where a.ppuerto = '".$input->puerto."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un puerto en la tabla puerto.
	*
	* @param string $input->puerto puerto para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarpuerto($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into puerto values('','".$input->puerto."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca una mercancia en la tabla mercancia.
	*
	* @param string $input->mercancia la mercancia que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarmercancia($input) {
		$this->conexion();
		$response = $this->mostrar("select a.mn, a.mmercancia from mercancia a where a.mmercancia = '"
		                           .$input->mercancia."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta una mercancia en la tabla mercancia.
	*
	* @param string $input->mercancia mercancia para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarmercancia($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into mercancia values('','".$input->mercancia."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un servicio en la tabla servicio.
	*
	* @param string $input->servicio el servicio que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarservicio($input) {
		$this->conexion();
		$response = $this->mostrar("select a.sn, a.sservicio from servicio a where a.sservicio = '"
		                           .$input->servicio."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un servicio en la tabla servicio.
	*
	* @param string $input->servicio puerto para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarservicio($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into servicio values('','".$input->servicio."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un imo en la tabla imo.
	*
	* @param string $input->imo el imo que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarimo($input) {
		$this->conexion();
		$response = $this->mostrar("select a.imon, a.imoimo from imo a where a.imoimo  = '".$input->imo."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un imo en la tabla imo.
	*
	* @param string $input->imo imo para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarimo($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into imo values('','".$input->imo."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un consignatario en la tabla consignatario.
	*
	* @param string $input->consignatario el consignatario que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarconsignatario($input) {
		$this->conexion();
		$response = $this->mostrar("select a.cn, a.cconsignatario from consignatario a where a.cconsignatario = '"
		                           .$input->consignatario."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un consignatario en la tabla consignatario.
	*
	* @param string $input->consignatario consignatario para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarconsignatario($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into consignatario values('','".$input->consignatario."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un manifiesto en la tabla manifiesto.
	*
	* @param int $input->itemid item del manifiesto
	* @param int $input->agenciaoperadorid relacion entre agencia y operador del manifiesto
	* @param int $input->naveid nave del manifiesto
	* @param string $input->viaje viaje del manifiesto
	* @param string $input->nrmfto numero del manifiesto
	* @param string $input->tipotransito transito del manifiesto
	* @param int $input->contenedorid contenedor del manifiesto
	* @param string $input->bl bl del manifiesto
	* @param int $input->puertoembarqueid puerto de embarque del manifiesto
	* @param int $input->puertodescargaid puerto de descarga del manifiesto
	* @param int $input->puertodestinoid destino en bolivia del manifiesto
	* @param int $input->mercanciaid mercancia del manifiesto
	* @param float $input->neto peso carga del manifiesto
	* @param float $input->bruto peso total de carga del manifiesto
	* @param int $input->servicioid servicio del manifiesto
	* @param int $input->imo imo del manifiesto
	* @param string $input->sellos sellos del manifiesto
	* @param int $input->bultos bultos del manifiesto
	* @param int $input->consiganatarioid consiganatario del manifiesto
	* @param string $input->estadorecepcion estado del manifiesto
	* @param string $input->periodo periodo de arrivo del manifiesto
	* @param datetime $input->fecha fecha cargado de arrivo del manifiesto
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarmanifiesto($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into manifiesto values('',".$input->itemid.",".$input->agenciaoperadorid
		.",".$input->naveid.",'".$input->viaje."','".$input->nrmfto."','".$input->tipotransito."',"
		.$input->contenedorid.",'".$input->bl."',".$input->puertoembarqueid.",".$input->puertodescargaid.","
		.$input->purtodestinoid.",".$input->mercanciaid.",".$input->neto.",".$input->bruto.",".$input->servicioid
		.",".$input->imoid.",'".$input->sellos."',".$input->bultos.",".$input->consignatarioid.",'"
		.$input->estadorecepcion."','".$input->periodo."','".$input->fecha."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca los manifiestos subidos en una fecha determinada en la tabla manifiesto.
	*
	* @param datetime $input->fecha la fecha de subida del manifiesto
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarmanifiestoxfecha($input) {
		$this->conexion();
		$response = $this->mostrar("select a.ititem, c.aagencia, e.nnave, b.mviaje, b.mnromfto, b.mtipotransito,".
		                           " f.cocontenedor, g.tctipo, h.opoperador, b.mbl, i.ppuerto, j.ppuerto, k.ppuerto,".
		                           " l.mmercancia, f.copeso, b.mneto, b.mbruto, m.sservicio, n.imoimo, b.msellos,".
		                           " b.mbultos, o.cconsignatario, b.mestado, b.mperiodo from item a, manifiesto b,".
		                           " agencia c, agenciaoperador d, nave e, contenedor f, tcontenedor g, operador h,".
		                           " puerto i, puerto j, puerto k, mercancia l, servicio m, imo n, consignatario o".
		                           " where b.mitiem = a.itn and b.magenciaoperador = d.aon and d.aoagencia = c.an".
		                           " and d.aooperador = h.opn and b.mnave = e.nn and b.mcontenedor = f.con and".
		                           " f.cotipo = g.tcn and b.mpuertoembarque = i.pn and b.mpuertodescarga = j.pn".
		                           " and b.mpuertodestino = k.pn and b.mmercancia = l.mn and b.mservicio = m.sn".
		                           " and b.mimo = n.imon and b.mconsignatario = o.cn and b.mfecha = '".
		                           $input->fecha."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca si un consignatario es asociado de la cni en la tabla asociado.
	*
	* @param string $input->consignatario el consignatario que desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarasociadoxconsignatario($input) {
		$this->conexion();
		$response = $this->mostrar("select a.asasociado, a.asrazonsocial, a.asrotulo, a.asmailprincipal,".
		                           " a.asmailssecundarios from asociado a, consignatario b where".
		                           " a.asasociado = b.cn and b.cconsignatario = '".$input->consignatario."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca los manifiesto de un determinado consignatario en la tabla manifiesto.
	*
	* @param datetime $input->fecha fecha en la que se subio el manifiesto que desea buscar
	* @param int $input->consignatarioid el consignatario del cual se quiere buscar sus manifiesto
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarmanifiestodeconsignatario($input) {
		$this->conexion();
		$response = $this->mostrar("select b.nnave, a.mnromfto, c.tctipo, e.mmercancia, d.copeso, a.mneto, a.mbruto,".
		                           " a.mtipotransito, g.opoperador, d.cocontenedor, a.mbl, h.ppuerto, i.ppuerto,".
		                           " j.ppuerto, k.sservicio, a.msellos, l.imoimo, a.mbultos from manifiesto a,".
		                           " nave b, tcontenedor c, contenedor d, mercancia e, agenciaoperador f, operador".
		                           " g, puerto h, puerto i, puerto j, servicio k, imo l where a.mnave = b.nn and".
		                           " a.mcontenedor = d.con and d.cotipo = c.tcn and a.mmercancia = e.mn and".
		                           " a.magenciaoperador = f.aon and f.aooperador = g.opn and a.mpuertoembarque = h.pn".
		                           " and a.mpuertodescarga = i.pn and a.mpuertodestino = j.pn and a.mservicio = k.sn".
		                           " and a.mimo = l.imon and a.mconsignatario = ".$input->consignatarioid." and".
		                           " a.mfecha = '".$input->fecha."'");
		$this->desconexion();
		return $response;
	}
}
$server = new SoapServer("http://127.0.0.1:12/wsdl/db.wsdl");
$server->setClass("db");
$server->handle();

