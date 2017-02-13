<?php

$expected     = array('function __construct() { /**/ } ');

$expected_not = array(  'function isset( ) { /**/ } ',
                        'function set_state( ) { /**/ } ',
                        'function set( ) { /**/ } ',
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
)

?>