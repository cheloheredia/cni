<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
require_once('wsdl.php');
require_once('../ini/ini.php');
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

	/**
	* Esta funcion busca el turno de la tabla turno.
	*
	* @param datetime $input->turno turno que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarturno($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tn, a.tturno from turno a where a.tturno = '".$input->turno."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un turno en la tabla turno.
	*
	* @param string $input->turno turno para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarturno($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into turno values('','".$input->turno."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un almacen de la tabla almacen.
	*
	* @param string $input->almacen almacen que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaralmacen($input) {
		$this->conexion();
		$response = $this->mostrar("select a.aln, a.alalmacen from almacen a where a.alalmacen = '".$input->almacen."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un almacen en la tabla almacen.
	*
	* @param string $input->almacen turno para insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaralmacen($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into almacen values('','".$input->almacen."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un lugar de la tabla lugar.
	*
	* @param int $input->almacen almacen que se desea buscar
	* @param string $input->lugar lugar que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarlugar($input) {
		$this->conexion();
		$response = $this->mostrar("select a.ln, a.lalmacen, a.llugar from lugar a where a.lalmacen = ".$input->almacen.
		                           " and a.llugar = '".$input->lugar."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un lugar en la tabla lugar.
	*
	* @param int $input->almacen almacen que se desea insertar
	* @param string $input->lugar lugar que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarlugar($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into lugar values('',".$input->almacen.",'".$input->lugar."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un transportista de la tabla transportista.
	*
	* @param string $input->transportista transportista que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscartransportista($input) {
		$this->conexion();
		$response = $this->mostrar("select a.transn, a.transtransportista from transportista a where".
		                           " a.transtransportista = '".$input->transportista."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un transportista en la tabla transportista.
	*
	* @param string $input->transportista transportista que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertartransportista($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into transportista values('','".$input->transportista."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un camion de la tabla camion.
	*
	* @param string $input->camion camion que se desea buscar
	* @param int $input->transportista transportista que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarcamion($input) {
		$this->conexion();
		$response = $this->mostrar("select a.can, a.caplaca, a.catransportista from camion a where a.caplaca = '".
		                           $input->camion."' and a.catransportista = ".$input->transportista);
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un camion en la tabla camion.
	*
	* @param int $input->transportista transportista que se desea insertar
	* @param string $input->camion camion que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarcamion($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into camion values('','".$input->camion."',".$input->transportista.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un tipo de bulto de la tabla tbulto.
	*
	* @param string $input->tipo tipo de bulto que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscartipobulto($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tbn, a.tbtipo from tbulto a where a.tbtipo = '".$input->tipo."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un tipo de bulto en la tabla tbulto.
	*
	* @param string $input->tipo tipo de bulto que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertartipobulto($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into tbulto values('','".$input->tipo."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un bulto de la tabla bulto.
	*
	* @param int $input->tipo tipo de bulto que se desea buscar
	* @param string $input->bulto bulto que se desea buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarbulto($input) {
		$this->conexion();
		$response = $this->mostrar("select a.bn, a.bbulto, a.btipo from bulto a where a.bbulto = '".$input->bulto.
		                           "' and a.btipo = ".$input->tipo);
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un bulto en la tabla bulto.
	*
	* @param int $input->tipo tipo de bulto que se desea insertar
	* @param string $input->butlo bulto que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarbutlo($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into bulto values('','".$input->bulto."',".$input->tipo.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un planificacion en la tabla planificacion.
	*
	* @param int $input->turno turno de la planificacion que se desea insertar
	* @param int $input->lugar lugar de la planificacion que se desea insertar
	* @param int $input->contenedor contenedor de la planificacion que se desea insertar
	* @param int $input->camion camion de la planificacion que se desea insertar
	* @param string $input->matriz matriz de la planificacion que se desea insertar
	* @param string $input->cantidad cantidad de la planificacion que se desea insertar
	* @param string $input->peso peso de la planificacion que se desea insertar
	* @param int $input->butlo bulto de la planificacion que se desea insertar
	* @param string $input->baroti baroti de la planificacion que se desea insertar
	* @param int $input->destino destion de la planificacion que se desea insertar
	* @param datetime $input->fecha fecha de la planificacion que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarplanificacion($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into planificacion values('',".$input->turno.",".$input->lugar.",".
		    $input->contenedor.",".$input->camion.",'".$input->matriz."','".$input->cantidad."','".
		    $input->peso."',".$input->bulto.",'".$input->baroti."',".$input->destino.",'".$input->fecha."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca las planificacion subidas en una fecha determinada en la tabla planificacion.
	*
	* @param datetime $input->fecha la fecha de subida de la planificacion
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarplanificacionxfecha($input) {
		$this->conexion();
		$response = $this->mostrar("select b.tturno, c.alalmacen, e.cocontenedor, f.tctipo, g.caplaca, a.plmatriz,".
		                           " a.plcantidad, a.plpeso, i.bbulto, j.tbtipo, d.llugar, a.plbaroti,".
		                           " h.transtransportista, k.ppuerto from planificacion a, turno b, almacen c,".
		                           " lugar d, contenedor e, tcontenedor f, camion g, transportista h, bulto i,".
		                           " tbulto j, puerto k where a.plturno = b.tn and a.pllugar = d.ln and".
		                           " d.lalmacen = c.aln and a.plcontenedor = e.con and e.cotipo = f.tcn and".
		                           " a.plcamion = g.can and g.catransportista = h.transn and a.plbulto = i.bn and".
		                           " i.btipo = j.tbn and a.pldestino = k.pn and a.plfecha = '".$input->fecha."' order".
		                           " by b.tturno, c.alalmacen");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca los recintos que coincidan de la tabla recinto.
	*
	* @param string $input->recinto recinto que se quere buscar coincidencias
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarrecintosac($input) {
		$this->conexion();
		$response = $this->mostrar("select a.ren, a.rerecinto from recinto a where a.rerecinto like '%".
		                           $input->recinto."%' limit 10");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca el tipo de mercancio de la tabla tmercanciadab.
	*
	* @param string $input->tipo tipo que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscartmercanciadab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tmdn, a.tmdtipo from tmercanciadab a where a.tmdtipo = '".$input->tipo."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un tipo de mercancia dab en la tabla tmercanciadab.
	*
	* @param int $input->tipo tipo de mercacia dab que se desea insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertartmercanciadab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into tmercanciadab values('','".$input->tipo."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca la mercancia de la tabla mercanciadab.
	*
	* @param string $input->mercancia mercancia que se quere buscar
	* @param int $input->tipo tipo que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarmercanciadab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.mdn, a.mdmercancia, a.mdtipo from mercanciadab a where a.mdmercancia = '".
		                           $input->mercancia."' and a.mdtipo = ".$input->tipo);
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta una mercancia dab en la tabla mercanciadab.
	*
	* @param string $input->mercancia mercancia que se quere insertar
	* @param int $input->tipo tipo que se quere insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarmercaciadab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into mercanciadab values('','".$input->mercancia."',".$input->tipo.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca el alamacendab de la tabla almacendab.
	*
	* @param string $input->almacen almacen que se quere buscar
	* @param int $input->recinto recinto que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscaralmacendab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.adn, a.adalamacen, a.adrecinto from almacendab a where a.adalamacen = '".
		                           $input->almacen."' and a.adrecinto = ".$input->recinto);
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un almacen dab en la tabla almacendab.
	*
	* @param string $input->almacen almacen que se quere insertar
	* @param int $input->recinto recinto que se quere insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertaralmacendab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into almacendab values('','".$input->almacen."',".$input->recinto.")")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca el tipo de deposito de la tabla tdposito.
	*
	* @param string $input->tipo tipo de deposito que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscartdeposito($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tdn, a.tdtipo from tdeposito a where a.tdtipo = '".$input->tipo."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un tipo de deposito dab en la tabla tdeposito.
	*
	* @param string $input->tipo tipo de deposito que se quere buscar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertartdeposito($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into tdeposito values('','".$input->tipo."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un estado dab de la tabla estadodab.
	*
	* @param string $input->estado tipo de deposito que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarestadodab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.edn, a.edestado from estadodab a where a.edestado = '".$input->estado."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un estado dab en la tabla estadodab.
	*
	* @param string $input->estado estado que se quere insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarestadodab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into estadodab values('','".$input->estado."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un camion dab de la tabla camiondab.
	*
	* @param string $input->camion tipo de deposito que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarcamiondab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.cdn, a.cdcamion from camiondab a where a.cdcamion = '".$input->camion."'");
		$this->desconexion();
		return $response;
	}


	/**
	* Esta funcion inserta un camion dab en la tabla camiondab.
	*
	* @param string $input->camion estado que se quere insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarcamiondab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into camiondab values('','".$input->camion."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion busca un reporte dab de la tabla reportedab.
	*
	* @param string $input->viaje reporte que se quere buscar
	* @return int resquery->error que es 0 cuando no existe un error
	*		  matriz resquery->matriz que contiene el resultado de la consulta
	*/
	public function buscarreportedab($input) {
		$this->conexion();
		$response = $this->mostrar("select a.rdn, a.rdviaje, a.rdingreso, a.rdembarque, a.rditem, a.rdfechaingreso, ".
		                           "a.rdfechabalanza, rdfechaprecepcion, a.rdfechasalida, a.rdconsignatario, ".
		                           "a.rdbultosman, a.rdpesoman, a.rdbultosrec, a.rdpesorec, a.rdsaldopeso, ".
		                           "a.rdsaldobultos, a.rddescripcion, a.rdalmacen, a.rdregistrodeposito, ".
		                           "a.rdfechavenc, a.rdestado, a.rddvi, a.rdcamion, a.rdchasis from reportedab a ".
		                           "where a.rdviaje = '".$input->viaje."'");
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion actualiza un reporte dab en la tabla reportedab.
	*
	* @param int $input->id id del reporte a actualizar
	* @param string $input->ingreso ingreso del reporte a actualizar
	* @param string $input->enbarque enbarque del reporte a actualizar
	* @param string $input->item item del reporte a actualizar
	* @param date $input->fechaingreso fecha de ingreso a recinto del reporte a actualizar
	* @param date $input->fechabalanza fecha de paso por balanza del reporte a actualizar
	* @param date $input->fecharecepcion fecha del parte por recepcion del reporte a actualizar
	* @param date $input->fechasalida fecha de la salida del reporte a actualizar
	* @param int $input->consignatarioid consignatario del reporte a actualizar
	* @param string $input->bultosmanifiesto bultos manifestados del reporte a actualizar
	* @param string $input->pesomanifestado peso manifestado del reporte a actualizar
	* @param string $input->bultosrecevidos bultos recibidos del reporte a actualizar
	* @param string $input->pesorecivido peso recibido del reporte a actualizar
	* @param string $input->saldopeso saldo de peso del reporte a actualizar
	* @param string $input->saldobultos saldo de bultos del reporte a actualizar
	* @param int $input->descripcionid descripcion del reporte a actualizar
	* @param int $input->almacenid almacen del reporte a actualizar
	* @param int $input->registrodepositoid tipo de deposito del reporte a actualizar
	* @param date $input->fechavencimiento fecha de vencimiento del reporte a actualizar
	* @param int $input->estadoid estado del reporte a actualizar
	* @param string $input->dvi dvi del reporte a actualizar
	* @param int $input->camionid camion del reporte a actualizar
	* @param string $input->chasis chasis del reporte a actualizar
	* @param datetime $input->fecha fecha del reporte a actualizar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function actualizarreportedab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("update reportedab set rdingreso = '".$input->ingreso."', rdembarque = '".
		    $input->enbarque."', rditem = '".$input->item."', rdfechaingreso = '".$input->fechaingreso.
		    "', rdfechabalanza = '".$input->fechabalanza."', rdfechaprecepcion = '".$input->fecharecepcion.
		    "', rdfechasalida = '".$input->fechasalida."', rdconsignatario = ".$input->consignatarioid.
		    ", rdbultosman = '".$input->bultosmanifiesto."', rdpesoman = '".$input->pesomanifestado.
		    "', rdbultosrec = '".$input->bultosrecevidos."', rdpesorec = '".$input->pesorecivido."', rdsaldopeso = '".
		    $input->saldopeso."', rdsaldobultos = '".$input->saldobultos."', rddescripcion = ".$input->descripcionid.
		    ", rdalmacen = ".$input->almacenid.", rdregistrodeposito = ".$input->registrodepositoid.", rdfechavenc = '".
		    $input->fechavencimiento."', rdestado = ".$input->estadoid.", rddvi = '".$input->dvi."', rdcamion = ".
		    $input->camionid.", rdchasis = '".$input->chasis."', rdfecha = '".$input->fecha."' where rdn = ".$input->id)) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
	* Esta funcion inserta un reporte dab en la tabla reportedab.
	*
	* @param string $input->viaje viaje del reporte a insertar
	* @param string $input->ingreso ingreso del reporte a insertar
	* @param string $input->enbarque enbarque del reporte a insertar
	* @param string $input->item item del reporte a insertar
	* @param date $input->fechaingreso fecha de ingreso a recinto del reporte a insertar
	* @param date $input->fechabalanza fecha de paso por balanza del reporte a insertar
	* @param date $input->fecharecepcion fecha del parte por recepcion del reporte a insertar
	* @param date $input->fechasalida fecha de la salida del reporte a insertar
	* @param int $input->consignatarioid consignatario del reporte a insertar
	* @param string $input->bultosmanifiesto bultos manifestados del reporte a insertar
	* @param string $input->pesomanifestado peso manifestado del reporte a insertar
	* @param string $input->bultosrecevidos bultos recibidos del reporte a insertar
	* @param string $input->pesorecivido peso recibido del reporte a insertar
	* @param string $input->saldopeso saldo de peso del reporte a insertar
	* @param string $input->saldobultos saldo de bultos del reporte a insertar
	* @param int $input->descripcionid descripcion del reporte a insertar
	* @param int $input->almacenid almacen del reporte a insertar
	* @param int $input->registrodepositoid tipo de deposito del reporte a insertar
	* @param date $input->fechavencimiento fecha de vencimiento del reporte a insertar
	* @param int $input->estadoid estado del reporte a insertar
	* @param string $input->dvi dvi del reporte a insertar
	* @param int $input->camionid camion del reporte a insertar
	* @param string $input->chasis chasis del reporte a insertar
	* @param datetime $input->fecha fecha del reporte a insertar
	* @return int res->res que es 0 cuando no existe un error
	*
	*/
	public function insertarreportedab($input) {
		$this->conexion();
		$response = new res();
		if ($this->ejecutarquery("insert into reportedab values('', '".$input->viaje."', '".$input->ingreso."', '".
		    $input->enbarque."', '".$input->item."', '".$input->fechaingreso."', '".$input->fechabalanza."', '".
		    $input->fecharecepcion."', '".$input->fechasalida."', ".$input->consignatarioid.", '".
		    $input->bultosmanifiesto."', '".$input->pesomanifestado."', '".$input->bultosrecevidos."', '".
		    $input->pesorecivido."', '".$input->saldopeso."', '".$input->saldobultos."', ".$input->descripcionid.", ".
		    $input->almacenid.", ".$input->registrodepositoid.", '".$input->fechavencimiento."', ".$input->estadoid.
		    ", '".$input->dvi."', ".$input->camionid.", '".$input->chasis."', '".$input->fecha."')")) {
			$response->res = 0;
		} else {
			$response->res = 1;
		}
		$this->desconexion();
		return $response;
	}

	/**
    * Esta funcion busca los dabs subidos en una fecha determinada en la tabla reportedab.
    *
    * @param datetime $input->fecha la fecha de subida del manifiesto
    * @return int resquery->error que es 0 cuando no existe un error
    *         matriz resquery->matriz que contiene el resultado de la consulta
    */
    public function buscardabfecha($input) {
        $this->conexion();
        $response = $this->mostrar("select a.rdn, a.rdviaje, a.rdingreso,a.rditem, a.rdfechaingreso, a.rdfechabalanza,".
                                   " a.rdfechaprecepcion, a.rdfechasalida, b.cconsignatario, a.rdbultosman,".
                                   " a.rdpesoman, a.rdbultosrec, a.rdpesorec, a.rdsaldopeso, a.rdsaldobultos,".
                                   " c.mdmercancia, d.adalamacen, e.tdtipo, f.tmdtipo, a.rdfechavenc, g.edestado,".
                                   " a.rddvi, h.cdcamion, a.rdchasis, i.rerecinto from reportedab a, consignatario b,".
                                   " mercanciadab c, almacendab d, tdeposito e, tmercanciadab f, estadodab g,".
                                   " camiondab h, recinto i where a.rdconsignatario = b.cn and a.rddescripcion = c.mdn".
                                   " and c.mdtipo = f.tmdn and a.rdalmacen = d.adn and d.adrecinto = i.ren and".
                                   " a.rdregistrodeposito = e.tdn and a.rdestado = g.edn and a.rdcamion = h.cdn and".
                                   " a.rdfecha = '".$input->fecha."'");
        $this->desconexion();
        return $response;
    }
}
$server = new SoapServer($dbsdir."/wsdl/db.wsdl");
$server->setClass("db");
$server->handle();

