<?php

$expected     = array('pack(\'n v c*\', $b)',
                      'pack(\'Tvc*\', $b)',
                      'pack(\'c2chars/Tint\', $b)',
                      'pack(\'cchars/nint\', $b)',
                      'pack(\'cchars/n\', $b)',
                      'pack(\'cnint\', $b)',
                      'pack(\'c2chars/nint\', $b)',
                      'pack(\'cchars /n\', $b)',
                     );

$expected_not = array('pack(\'nvc\', $b)',
                      'pack(\'n2v3c4\', $b)',
                      'pack(\'v\', $b)',
                      'pack(\'\', $b)',
                      'pack(\'n2v*c4\', $b)',
                      'pack(\'n2v*c@\', $b)',
                      'pack(\'nvc*\', $b)',
                      'pack(\'nvc@\', $b)',
                     );

?>