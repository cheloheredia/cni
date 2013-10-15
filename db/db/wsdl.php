<?php

// PHP classes corresponding to the data types in defined in WSDL

class res {

    /**
     * @var int
     */
    public $res;

}

class resquery {

    /**
     * @var array of filas
     */
    public $matriz;

    /**
     * @var int
     */
    public $error;

}

class filas {

    /**
     * @var array of string
     */
    public $columnas;

}

class buscaritementradas {

    /**
     * @var string
     */
    public $item;

}

class insertaritementradas {

    /**
     * @var string
     */
    public $item;

}

class buscaragenciaentradas {

    /**
     * @var string
     */
    public $agencia;

}

class insertaragenciaentradas {

    /**
     * @var string
     */
    public $agencia;

}

class buscaroperadorentradas {

    /**
     * @var string
     */
    public $operador;

}

class insertaroperadorentradas {

    /**
     * @var string
     */
    public $operador;

}

class buscaragenciaoperadorentradas {

    /**
     * @var int
     */
    public $agenciaid;

    /**
     * @var int
     */
    public $operadorid;

}

class insertaragenciaoperadorentradas {

    /**
     * @var int
     */
    public $agenciaid;

    /**
     * @var int
     */
    public $operadorid;

}

class buscarnaveentradas {

    /**
     * @var string
     */
    public $nave;

}

class insertarnaveentradas {

    /**
     * @var string
     */
    public $nave;

    /**
     * @var int
     */
    public $operadorid;    

}

class buscartipocontenedorentradas {

    /**
     * @var string
     */
    public $tipo;

}

class insertartipocontenedorentradas {

    /**
     * @var string
     */
    public $tipo;

}

class buscarcontenedorentradas {

    /**
     * @var string
     */
    public $contenedor;

}

class insertarcontenedorentradas {

    /**
     * @var string
     */
    public $contenedor;

    /**
     * @var int
     */
    public $tipoid;

    /**
     * @var float
     */
    public $peso;

}

class buscarpuertoentradas {

    /**
     * @var string
     */
    public $puerto;

}

class insertarpuertoentradas {

    /**
     * @var string
     */
    public $puerto;

}

class buscarmercanciaentradas {

    /**
     * @var string
     */
    public $mercancia;

}

class insertarmercanciaentradas {

    /**
     * @var string
     */
    public $mercancia;

}

class buscarservicioentradas {

    /**
     * @var string
     */
    public $servicio;

}

class insertarservicioentradas {

    /**
     * @var string
     */
    public $servicio;

}

class buscarimoentradas {

    /**
     * @var float
     */
    public $imo;

}

class insertarimoentradas {

    /**
     * @var string
     */
    public $imo;

}

class buscarconsignatarioentradas {

    /**
     * @var string
     */
    public $consignatario;

}

class insertarconsignatarioentradas {

    /**
     * @var string
     */
    public $consignatario;

}

class insertarmanifiestoentradas {

    /**
     * @var int
     */
    public $itemid;

    /**
     * @var int
     */
    public $agenciaoperadorid;

    /**
     * @var int
     */
    public $naveid;

    /**
     * @var string
     */
    public $viaje;

    /**
     * @var string
     */
    public $nrmfto;

    /**
     * @var string
     */
    public $tipotransito;

    /**
     * @var int
     */
    public $contenedorid;

    /**
     * @var string
     */
    public $bl;

    /**
     * @var int
     */
    public $puertoembarqueid;

    /**
     * @var int
     */
    public $purtodestinoid;

    /**
     * @var int
     */
    public $puertodescargaid;

    /**
     * @var int
     */
    public $mercanciaid;

    /**
     * @var float
     */
    public $bruto;

    /**
     * @var float
     */
    public $neto;

    /**
     * @var int
     */
    public $servicioid;

    /**
     * @var int
     */
    public $imoid;

    /**
     * @var string
     */
    public $sellos;

    /**
     * @var float
     */
    public $bultos;

    /**
     * @var int
     */
    public $consignatarioid;

    /**
     * @var string
     */
    public $estadorecepcion;

    /**
     * @var string
     */
    public $periodo;

    /**
     * @var datetime
     */
    public $fecha;

}
// Class Map
$classMap = array(
	'res' => 'res',
	'resquery' => 'resquery',
	'filas' => 'filas',
    'buscaritementradas' => 'buscaritementradas',
    'insertaritementradas' => 'insertaritementradas',
    'buscaragenciaentradas' => 'buscaragenciaentradas',
    'insertaragenciaentradas' => 'insertaragenciaentradas',
    'buscaroperadorentradas' => 'buscaroperadorentradas',
    'insertaroperadorentradas' => 'insertaroperadorentradas',
    'buscaragenciaoperadorentradas' => 'buscaragenciaoperadorentradas',
    'insertaragenciaoperadorentradas' => 'insertaragenciaoperadorentradas',
    'buscarnaveentradas' => 'buscarnaveentradas',
    'insertarnaveentradas' => 'insertarnaveentradas',
    'buscartipocontenedorentradas' => 'buscartipocontenedorentradas',
    'insertartipocontenedorentradas' => 'insertartipocontenedorentradas',
    'buscarcontenedorentradas' => 'buscarcontenedorentradas',
    'insertarcontenedorentradas' => 'insertarcontenedorentradas',
    'buscarpuertoentradas' => 'buscarpuertoentradas',
    'insertarpuertoentradas' => 'insertarpuertoentradas',
    'buscarmercanciaentradas' => 'buscarmercanciaentradas',
    'insertarmercanciaentradas' => 'insertarmercanciaentradas',
    'buscarservicioentradas' => 'buscarservicioentradas',
    'insertarservicioentradas' => 'insertarservicioentradas',
    'buscarimoentradas' => 'buscarimoentradas',
    'insertarimoentradas' => 'insertarimoentradas',
    'buscarconsignatarioentradas' => 'buscarconsignatarioentradas',
    'insertarconsignatarioentradas' => 'insertarconsignatarioentradas',
    'insertarmanifiestoentradas' => 'insertarmanifiestoentradas'
);