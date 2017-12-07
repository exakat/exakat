<?php

$expected     = array('D\\A::methodcall( )',
                      'D\\A::$property',
                      'D\\A::constante',
                      'catch (D\\A $e) { /**/ } ',
                      'D\\A $a',
                      '$a instanceof D\\A',
                     );

$expected_not = array('D\\b::methodcall( )',
                      'D\\b::$property',
                      'D\\b::constante',
                      'catch (D\\b $e) { /**/ } ',
                      'D\\b $a',
                      '$a instanceof D\\b',
                     );

?>