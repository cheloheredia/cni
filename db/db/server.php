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
}
$server = new SoapServer("http://127.0.0.1:12/wsdl/db.wsdl");
$server->setClass("db");
$server->handle();
