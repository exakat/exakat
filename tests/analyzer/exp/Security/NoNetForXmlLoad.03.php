<?php

$expected     = array('simplexml_load_string($string, \'asd\', LIBXML_NOENT)',
                      'simplexml_load_string($string, \'asd\', LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING)',
                      'simplexml_load_string($string, \'asd\', LIBXML_NOENT)',
                      'simplexml_load_string($string, \'asd\', 33)',
                      'simplexml_load_string($string, \'asd\', LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | B | C)',
                      'simplexml_load_string($string, \'asd\')',
                     );

$expected_not = array('simplexml_load_string($string, \'asd\', $options)',
                      'simplexml_load_string($string, \'asd\', LIBXML_NONET)',
                     );

?>