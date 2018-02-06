<?php

const MY_ARRAY = [1,2,3];
const MY_STRING = 'string';

$x = array(
    'good' => array( 
        'conf' => array(
            'type' => 'string',
        ),
    ),
    
    'bad' => array( 
        'conf' => array(
            'type' => array('key' => 'string'),
        ),
    ),

    'bad2' => array( 
        'conf' => array(
            'type' => 1,
        ),
    ),

    'bad3' => array( 
        'conf' => array(
            'type' => E_ALL,
        ),
    ),

    'bad4' => array( 
        'conf' => array(
            'type' => MY_ARRAY,
        ),
    ),

    'bad4' => array( 
        'conf' => array(
            'type' => MY_STRING,
        ),
    ),

    'bad4' => array( 
        'conf' => array(
            'type' => MY_UNDEFINED_ARRAY,
        ),
    ),
    
    );

?>