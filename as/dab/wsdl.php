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

/**
 * @var array
 */
$classMap = array(
	'mostrarrecintossalidas' => 'mostrarrecintossalidas',
	'recinto' => 'recinto',
    'subirentradas' => 'subirentradas',
    'subirsalidas' => 'subirsalidas'
);

