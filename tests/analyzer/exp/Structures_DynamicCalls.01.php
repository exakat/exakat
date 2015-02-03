<?php

$expected     = array("constant('PHP_VERSION')",
                      "constant('Stdclass::VERSION')",
                      '$$p2',
                      '$$o',
                      '$$o',
                      '$$dnv',
                      '$$dna',
                      '${$o}',
                      '$dnc( )',
                      '$cm2( )',
                      '$dnf($$dna[1])',
                      '$cms2( )',
                      'new $dnc( )',
                      '$o->$c',
                      '$c::$p',
                      '$c2::$$p2',
                      '${$o}::cms1( )',
                      '$c::$cms2( )');

$expected_not = array();

?>