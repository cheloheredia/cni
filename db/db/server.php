<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
//class definitions
require_once('wsdl.php');
//SERVER
class db {
	public $conexion;
	
	public function conexion() {
		$this->conexion = mysql_connect('127.0.0.1:3306', 'cni', '2c0n1i3');
		mysql_select_db('cni', $this->conexion);
	}
	
	public function desconexion() {
		mysql_close($this->conexion);
	}
	
	public function ejecutarquery($query) {
		mysql_real_escape_string($query);
		return(mysql_query($query));
	}
	
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
	
	public function buscaritem($input) {
		$this->conexion();
		$response = $this->mostrar("select a.itn, a.ititem from item a where a.ititem='".$input->item."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscaragencia($input) {
		$this->conexion();
		$response = $this->mostrar("select a.an, a.aagencia from agencia a where a.aagencia = '".$input->agencia."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscaroperador($input) {
		$this->conexion();
		$response = $this->mostrar("select a.opn, a.opoperador from operador a where a.opoperador = '".
		                           $input->operador."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscaragenciaoperador($input) {
		$this->conexion();
		$response = $this->mostrar("select a.aon, a.aoagencia, a.aooperador from agenciaoperador a where a.aoagencia=".
		                           $input->agenciaid." and a.aooperador = ".$input->operadorid);
		$this->desconexion();
		return $response;
	}

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

	public function buscarnave($input) {
		$this->conexion();
		$response = $this->mostrar("select a.nn, a.nnave, b.opoperador from nave a, operador ".
		                           "b where a.noperador = b.opn and a.nnave = '".$input->nave."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscartipocontenedor($input) {
		$this->conexion();
		$response = $this->mostrar("select a.tcn, a.tctipo from tcontenedor a where tctipo = '".$input->tipo."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarcontenedor($input) {
		$this->conexion();
		$response = $this->mostrar("select a.con, a.cocontenedor, a.copeso, b.tctipo from contenedor a,".
		                           " tcontenedor b where a.cotipo = b.tcn and a.cocontenedor = '".
		                           $input->contenedor."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarpuerto($input) {
		$this->conexion();
		$response = $this->mostrar("select a.pn, a.ppuerto from puerto a where a.ppuerto = '".$input->puerto."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarmercancia($input) {
		$this->conexion();
		$response = $this->mostrar("select a.mn, a.mmercancia from mercancia a where a.mmercancia = '"
		                           .$input->mercancia."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarservicio($input) {
		$this->conexion();
		$response = $this->mostrar("select a.sn, a.sservicio from servicio a where a.sservicio = '"
		                           .$input->servicio."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarimo($input) {
		$this->conexion();
		$response = $this->mostrar("select a.imon, a.imoimo from imo a where a.imoimo  = '".$input->imo."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarconsignatario($input) {
		$this->conexion();
		$response = $this->mostrar("select a.cn, a.cconsignatario from consignatario a where a.cconsignatario = '"
		                           .$input->consignatario."'");
		$this->desconexion();
		return $response;
	}

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

	public function buscarasociadoxconsignatario($input) {
		$this->conexion();
		$response = $this->mostrar("select a.asasociado, a.asrazonsocial, a.asrotulo, a.asmailprincipal,".
		                           " a.asmailssecundarios from asociado a, consignatario b where".
		                           " a.asasociado = b.cn and b.cconsignatario = '".$input->consignatario."'");
		$this->desconexion();
		return $response;
	}

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
