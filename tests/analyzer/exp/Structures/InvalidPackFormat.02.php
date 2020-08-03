<?php

$expected     = array('unpack(\'n v c*\', $b)',
                      'unpack(\'c2chars/Tint\', $b)',
                      'unpack(\'Tvc*\', $b)',
                      'unpack(\'cchars /n\', $b)',
                     );

$expected_not = array('unpack(\'cnint\', $b)',
                      'unpack(\'n2v*c4\', $b)',
                      'unpack(\'nvc*\', $b)',
                      'unpack(\'nvc@\', $b)',
                      'unpack(\'n2v*c@\', $b)',
                      'unpack(\'nvc\', $b)',
                      'unpack(\'n2v3c4\', $b)',
                      'unpack(\'cchars/nint\', $b)',
                      'unpack(\'cchars/n\', $b)',
                      'unpack(\'c2chars/nint\', $b)',
                      'unpack(\'v\', $b)',
                      'unpack(\'\', $b)',
                     );

?>