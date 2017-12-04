<?php

$expected     = array('function isset( ) { /**/ } ',
                      'function set_state( ) { /**/ } ',
                      'function sleep( ) { /**/ } ',
                      'function tostring( ) { /**/ } ',
                      'function unset( ) { /**/ } ',
                      'function wakeup( ) { /**/ } ',
                      'function __construtc( ) { /**/ } ',
                      'function __contsruct( ) { /**/ } ',
                      'function __cosntruct( ) { /**/ } ',
                      'function _consrtuct( ) { /**/ } ',
                      'function __consrtuct( ) { /**/ } ',
                      'function consturct( ) { /**/ } ',
                      'function __consturct( ) { /**/ } ',
                     );

$expected_not = array('function __construct() { /**/ } ',
                      'function set( ) { /**/ } ',
                     );

?>