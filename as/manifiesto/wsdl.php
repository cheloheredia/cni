<?php

// PHP classes corresponding to the data types in defined in WSDL

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

}

// Class Map
$classMap = array(
	'subirentradas' => 'subirentradas',
	'subirsalidas' => 'subirsalidas'
);