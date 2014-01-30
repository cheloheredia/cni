<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/
class mostrarrecintossalidas {

    /**
     * @var string
     */
    public $error;

    /**
     * @var array of recinto
     */
    public $recinto;

}

class recinto {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $recinto;

}

class subirentradas {

    /**
     * @var string
     */
    public $documento;

    /**
     * @var string
     */
    public $recinto;

}

class subirsalidas {

    /**
     * @var datetime
     */
    public $fecha;

    /**
     * @var string
     */
    public $error;

}

class mostrardabreciensubidosalidas {

    /**
     * @var array of dab
     */
    public $dab;

    /**
     * @var string
     */
    public $error;

}

class dabreporte {

    /**
     * @var array of reportedab
     */
    public $reporte;

    /**
     * @var string
     */
    public $recinto;

}

class reportedab {

    /**
     * @var string
     */
    public $viaje;

    /**
     * @var string
     */
    public $nroingreso;

    /**
     * @var string
     */
    public $item;

    /**
     * @var string
     */
    public $fechaingreso;

    /**
     * @var string
     */
    public $fechabalanza;

    /**
     * @var string
     */
    public $fecharecepcion;

    /**
     * @var string
     */
    public $fechasalida;

    /**
     * @var string
     */
    public $consignatario;

    /**
     * @var string
     */
    public $bultosman;

    /**
     * @var string
     */
    public $pesoman;

    /**
     * @var string
     */
    public $bultorec;

    /**
     * @var string
     */
    public $pesorec;

    /**
     * @var string
     */
    public $saldopeso;

    /**
     * @var string
     */
    public $saldobulto;

    /**
     * @var string
     */
    public $mercancia;

    /**
     * @var string
     */
    public $almacen;

    /**
     * @var string
     */
    public $deposito;

    /**
     * @var string
     */
    public $tmercancia;

    /**
     * @var string
     */
    public $fechavenc;

    /**
     * @var string
     */
    public $estado;

    /**
     * @var string
     */
    public $dui;

    /**
     * @var string
     */
    public $camion;

    /**
     * @var string
     */
    public $chasis;

}

/**
 * @var array
 */
$GLOBALS['classMapdab'] = array(
	'mostrarrecintossalidas' => 'mostrarrecintossalidas',
    'recinto' => 'recinto',
    'subirentradas' => 'subirentradas',
    'subirsalidas' => 'subirsalidas',
    'mostrardabreciensubidosalidas' => 'mostrardabreciensubidosalidas',
    'dabreporte' => 'dabreporte',
    'reportedab' => 'reportedab'
);

