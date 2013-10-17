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

	public function insertarmanifiesto($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into manifiesto values('',".$input->itemid.",".$input->agenciaoperadorid.",".$input->naveid.",'".$input->viaje."','".$input->nrmfto."','".$input->tipotransito."',".$input->contenedorid.",'".$input->bl."',".$input->puertoembarqueid.",".$input->puertodescargaid.",".$input->purtodestinoid.",".$input->mercanciaid.",".$input->neto.",".$input->bruto.",".$input->servicioid.",".$input->imoid.",'".$input->sellos."',".$input->bultos.",".$input->consignatarioid.",'".$input->estadorecepcion."','".$input->periodo."','".$input->fecha."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}
}
$server = new SoapServer("http://127.0.0.1:12/wsdl/db.wsdl");
$server->setClass("db");
$server->handle();
