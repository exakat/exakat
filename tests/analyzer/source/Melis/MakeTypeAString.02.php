<?php

class X {
const MY_ARRAY = [1,2,3];
const MY_STRING = 'string';
}

$x = array(
    'good' => array( 
        'conf' => array(
            'type' => X::MY_STRING,
        ),
    ),

    'ok' => array( 
        'conf' => array(
            'type' => X::MY_UNDEFINED,
        ),
    ),
    
    'bad' => array( 
        'conf' => array(
            'type' => X::MY_ARRAY,
        ),
    ),
    );

?>