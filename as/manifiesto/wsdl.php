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
     * @var array of manifiestomaritimo
     */
    public $manifiestomaritimo;

    /**
     * @var string
     */
    public $error;

}

class manifiestomaritimo {

    /**
     * @var string
     */
    public $item;

    /**
     * @var string
     */
    public $agencia;

    /**
     * @var string
     */
    public $nave;

    /**
     * @var string
     */
    public $viaje;

    /**
     * @var string
     */
    public $nromfto;

    /**
     * @var string
     */
    public $tipotransito;

    /**
     * @var string
     */
    public $contenedor;

    /**
     * @var string
     */
    public $tipocontenedor;

    /**
     * @var string
     */
    public $operador;

    /**
     * @var string
     */
    public $bl;

    /**
     * @var string
     */
    public $puertoembarque;

    /**
     * @var string
     */
    public $puertodescarga;

    /**
     * @var string
     */
    public $puertodestino;

    /**
     * @var string
     */
    public $mercancia;

    /**
     * @var string
     */
    public $tara;

    /**
     * @var string
     */
    public $neto;

    /**
     * @var string
     */
    public $bruto;

    /**
     * @var string
     */
    public $servicio;

    /**
     * @var string
     */
    public $imo;

    /**
     * @var string
     */
    public $sellos;

    /**
     * @var string
     */
    public $bultos;

    /**
     * @var string
     */
    public $consignatario;

    /**
     * @var string
     */
    public $estado;

    /**
     * @var string
     */
    public $periodo;

}

class generarpdfyenviarentradas {

    /**
     * @var datetime
     */
    public $fecha;

}

class generarpdfyenviarsalidas {

    /**
     * @var string
     */
    public $error;

}

/**
 * @var array
 */
$classMap = array(
	'subirentradas' => 'subirentradas',
	'subirsalidas' => 'subirsalidas',
    'mostrarreciensubidoentradas' => 'mostrarreciensubidoentradas',
    'mostrarreciensubidosalidas' => 'mostrarreciensubidosalidas',
    'manifiestomaritimo' => 'manifiestomaritimo',
    'generarpdfyenviarentradas' => 'generarpdfyenviarentradas',
    'generarpdfyenviarsalidas' => 'generarpdfyenviarsalidas'
);

