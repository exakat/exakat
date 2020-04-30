<?php

$expected     = array('fn &($fooo1) => bar1( )',
                      'fn &($fooo3) => bar3( )',
                      'fn &($fooo4) => bar4( )',
                      'fn &($a1) => 1',
                      'fn &($a3) => 1 + $a',
                      'fn &($a4) => E_ALL',
                     );

$expected_not = array('fn &($fooo4) => bar4( )',
                      'fn &($fooo2) => bar2( )',
                      'fn &(&$a2) => $a2',
                     );

?>