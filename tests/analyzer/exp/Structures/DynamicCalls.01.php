<?php

$expected     = array("constant('PHP_VERSION')",
                      "constant('Stdclass::VERSION')",
                      '$$p2',
                      '$$o1',
                      '$$o4',
                      '$$dnv',
                      '${$o}',
                      '$dnf($$dna[1])',
                      'new $dnc( )',
                      '$o2->$c',
                      '$c::$p',
                      '$c2::$$p2',
                      '${$o}::cms1( )',
                      '$c::$cms2( )',
                      '$o3->$cm2( )',
                      '$c::$cms2( )', 
                      '$c2::$$p2', 
                      '$$dna[1]'
                      );

$expected_not = array(
                      '$dnc( )', // Is already in via new $dnc()
);

?>