<?php

$expected     = array('new x4',
                      'new x4( )',
                      'new \\x4',
                      'new \\x4( )',
                     );

$expected_not = array('new x3',
                      'new x3( )',
                      'new \\x3',
                      'new \\x3( )',
                      'new x2',
                      'new x2( )',
                      'new \\x2',
                      'new \\x2( )',
                     );

?>