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
// Class Map
$classMap = array(
	'res' => 'res',
	'resquery' => 'resquery',
	'filas' => 'filas',
    'buscaritementradas' => 'buscaritementradas',
    'insertaritementradas' => 'insertaritementradas'
);