<?php

$expected     = array('function trim4(string $f = C ? D : null) { /**/ } ',
                      'function trim5(string $f = C ? \D : null) { /**/ } ',
                      'function trim7(string $f = C ? x::F : null) { /**/ } ',
                     );

$expected_not = array('function trim1(string $a) { /**/ } ',
                      'function trim2(int $b = E::D) { /**/ } ',
                      'function trim3(string $f = null) { /**/ } ',
                      'function trim6(string $f = C ? E : null) { /**/ } ',
                     );

?>