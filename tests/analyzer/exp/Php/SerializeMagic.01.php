<?php

$expected     = array('function __serialize( ) { /**/ } ',
                      'function __unserialize( ) { /**/ } ',
                      'function __SERialize( ) { /**/ } ',
                      'function __unSERialize( ) { /**/ } ',
                     );

$expected_not = array('function _serialize( ) { /**/ } ',
                      'function _unserialize( ) { /**/ } ',
                     );

?>