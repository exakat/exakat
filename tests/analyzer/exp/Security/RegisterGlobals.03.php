<?php

$expected     = array('parse_str($a->b( )->c( ))',
                      '\\extract($_GET)',
                     );

$expected_not = array('parse_str($a->b()->c(), $d)',
                      '$c->parse_str($a->b()->c())',
                      'extract($_POST)',
                     );

?>