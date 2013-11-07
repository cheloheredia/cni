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


/**
 * @var array
 */
$GLOBALS['classMapplanificacion'] = array(
	'subirentradas' => 'subirentradas',
	'subirsalidas' => 'subirsalidas',
);

