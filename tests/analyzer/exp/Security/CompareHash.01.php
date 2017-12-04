<?php

$expected     = array('\'0\' != hash(\'md5\', \'240610708\', false)',
                      'hash(\'md5\', \'240610708\', false) == \'0\'',
                      'if(hash(\'alder32\', \'00e00099\', false)) { /**/ } elseif(hash(\'crc32\', \'2332\', false)) { /**/ } ',
                      'elseif(hash(\'crc32\', \'2332\', false)) { /**/ } ',
                     );

$expected_not = array('if (md5(\'240610708\') === \'0\') { /**/ }',
                     );

?>