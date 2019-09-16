<?php

$expected     = array('if($a === 3) { /**/ } elseif($a === \'string\') { /**/ } else { /**/ } ',
                      'elseif($a === \'string\') { /**/ } else { /**/ } ',
                     );

$expected_not = array('',
                      '',
                     );

?>