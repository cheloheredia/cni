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
    'insertarcontenedorentradas' => 'insertarcontenedorentradas'
);