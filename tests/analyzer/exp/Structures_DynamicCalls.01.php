<?php

$expected     = array("constant('PHP_VERSION')",
                      "constant('Stdclass::VERSION')",
                      '$$p2',
                      '$$o',
                      '$$o',
                      '$$dnv',
                      '$$dna[1]',
                      '${$o}',
                      '$dnc( )',
                      '$dnf($$dna[1])',
                      'new $dnc( )',
                      '$o->$c',
                      '$c::$p',
                      '$c2::$$p2',
                      '${$o}::cms1( )',
                      '$c::$cms2( )',
                      '$o->$cm2( )');

$expected_not = array();

?>