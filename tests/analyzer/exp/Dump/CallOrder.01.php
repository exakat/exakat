<?php

$expected     = array(
    array (
            'calling' => '\fooa',
            'callingName' => 'function fooA( ) { /**/ } ',
            'called' => '\foob',
            'calledName' => 'function fooB( ) { /**/ } ',
        ),

    array (
            'calling' => '\fooa',
            'callingName' => 'function fooA( ) { /**/ } ',
            'called' => '\fooc',
            'calledName' => 'function fooC( ) { /**/ } ',
        ),

    array (
            'calling' => '\foob',
            'callingName' => 'function fooB( ) { /**/ } ',
            'called' => '\fooc',
            'calledName' => 'function fooC( ) { /**/ } ',
        ),

    array (
            'calling' => '\foob',
            'callingName' => 'function fooB( ) { /**/ } ',
            'called' => '\fooa',
            'calledName' => 'function fooA( ) { /**/ } ',
        ),

);

$expected_not = array(array()
                     );

?>