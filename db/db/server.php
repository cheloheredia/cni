<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
//class definitions
require_once('wsdl.php');
//SERVER
class db {
	public $conexion; //we set this variable as global
	
	public function conexion() { //funcion pa realizar la conexion a la bd
		$this->conexion = mysql_connect('127.0.0.1:3306', 'cni', '2c0n1i3'); //realiza la conexion con el servidor
		mysql_select_db('cni', $this->conexion); //escoje la bd donde se va a trabajar
	}
	
	public function desconexion() { //funcion pa cerra la conexion a la bd
		mysql_close($this->conexion);  //cierra la conexion
	}
	
	public function ejecutarquery($query) { //funcion pa ejecutar cualquier query
		mysql_real_escape_string($query); //transoforma el query en un string comprensible pa el servidor
		return(mysql_query($query)); //ejecuta el query
	}
	
	public function mostrar($query) { //funcion pa mostrar los datos de un query
		$sqlmatriz = new resquery(); //inicimas el tipo de la matriz
		$sql = $this->ejecutarquery($query); //query pa validar la existencia
		if (mysql_num_rows($sql) > 0) { //verifica si se obtuvo algun dato
			for ($j = 0; $j < mysql_num_rows($sql); $j++) { //para recorrer toda la lista
				$row = mysql_fetch_row($sql); //recorremos el resultado fila por fila
				for ($i = 0; $i < mysql_num_fields($sql); $i++) { //recorremos el resultado columna por columna
					$sqlmatriz->matriz[$j]->columnas[$i] = $row[$i]; //guardamos cada campo en la matriz
				}
			}
			$sqlmatriz->error = 0; //guardamos que no existio ningun error
			return $sqlmatriz; //retornamos el resultado del query
		} else {
			$sqlmatriz->error = 1; //guardamos que existio ningun error
			return $sqlmatriz;
		} //si no si obtuvo ningun resultado
	}
	
	public function buscaritem($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.itn, a.ititem from item a where a.ititem='".$input->item."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertaritem($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into item values('','".$input->item."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function buscaragencia($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.an, a.aagencia from agencia a where a.aagencia = '".$input->agencia."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertaragencia($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into agencia values('','".$input->agencia."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function buscaroperador($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.opn, a.opoperador from operador a where a.opoperador = '".$input->operador."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertaroperador($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into operador values('','".$input->operador."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function buscaragenciaoperador($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.aon, a.aoagencia, a.aooperador from agenciaoperador a where a.aoagencia = ".$input->agenciaid." and a.aooperador = ".$input->operadorid);
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertaragenciaoperador($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into agenciaoperador values('',".$input->agenciaid.",".$input->operadorid.")")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function buscarnave($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.nn, a.nnave, b.opoperador from nave a, operador b where a.noperador = b.opn and a.nnave = '".$input->nave."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarnave($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into nave values('','".$input->nave."',".$input->operadorid.")")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscartipocontenedor($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.tcn, a.tctipo from tcontenedor a where tctipo = '".$input->tipo."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertartipocontenedor($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into tcontenedor values('','".$input->tipo."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarcontenedor($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.con, a.cocontenedor, a.copeso, b.tctipo from contenedor a, tcontenedor b where a.cotipo = b.tcn and a.cocontenedor = '".$input->contenedor."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarcontenedor($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into contenedor values('','".$input->contenedor."',".$input->tipoid.",".$input->peso.")")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarpuerto($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.pn, a.ppuerto from puerto a where a.ppuerto = '".$input->puerto."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarpuerto($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into puerto values('','".$input->puerto."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarmercancia($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.mn, a.mmercancia from mercancia a where a.mmercancia = '".$input->mercancia."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarmercancia($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into mercancia values('','".$input->mercancia."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarservicio($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.sn, a.sservicio from servicio a where a.sservicio = '".$input->servicio."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarservicio($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into servicio values('','".$input->servicio."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarimo($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.imon, a.imoimo from imo a where a.imoimo  = '".$input->imo."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarimo($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into imo values('','".$input->imo."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
	}

	public function buscarconsignatario($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = $this->mostrar("select a.cn, a.cconsignatario from consignatario a where a.cconsignatario = '".$input->consignatario."'");
		$this->desconexion(); //we do the desconection
		return $response; //we sent back the resquery
	}

	public function insertarconsignatario($input) { //function to save the session
		$this->conexion(); //we start the conection to the db
		$response = new res(); //we start the response type
		if ($this->ejecutarquery("insert into consignatario values('','".$input->consignatario."')")) { //preguntamos se se ejecuto el query de manera correcta
			$response->res = 0; //devolvemos que se ejecuto el query correctamente
		} else { //si no se ejecuto el query correctamente
			$response->res = 1; //notificamos que se ejecuto el query de manera incorrecta
		}
		$this->desconexion(); //we do the desconection*/
		return $response; //we sent back the resquery
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
