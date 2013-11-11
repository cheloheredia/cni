<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
class subirentradas {

    /**
     * @var string
     */
    public $documento;

}

class subirsalidas {

    /**
     * @var string
     */
    public $error;

    /**
     * @var detetime
     */
    public $fecha;

}

class mostrarreciensubidoentradas {

    /**
     * @var datetime
     */
    public $fecha;

}

class mostrarreciensubidosalidas {

    /**
     * @var string
     */
    public $error;

    /**
     * @var array of turno
     */
    public $planificacion;
    
}

class turno {

    /**
     * @var string
     */
    public $turno;

    /**
     * @var array of almacen
     */
    public $almacen;
    
}

class almacen {

    /**
     * @var string
     */
    public $almacen;

    /**
     * @var array of tabla
     */
    public $tabla;
    
}

class tabla {

    /**
     * @var string
     */
    public $contenedor;

    /**
     * @var string
     */
    public $tipo;

    /**
     * @var string
     */
    public $placa;

    /**
     * @var string
     */
    public $matriz;

    /**
     * @var string
     */
    public $cantidad;

    /**
     * @var string
     */
    public $peso;

    /**
     * @var string
     */
    public $descripcion;

    /**
     * @var string
     */
    public $tipobulto;

    /**
     * @var string
     */
    public $lugar;

    /**
     * @var string
     */
    public $baroti;

    /**
     * @var string
     */
    public $transportista;

    /**
     * @var string
     */
    public $destino;
    
}

/**
 * @var array
 */
$GLOBALS['classMapplanificacion'] = array(
	'subirentradas' => 'subirentradas',
    'subirsalidas' => 'subirsalidas',
    'mostrarreciensubidoentradas' => 'mostrarreciensubidoentradas',
    'mostrarreciensubidosalidas' => 'mostrarreciensubidosalidas',
    'turno' => 'turno',
    'almacen' => 'almacen',
    'tabla' => 'tabla'
);

