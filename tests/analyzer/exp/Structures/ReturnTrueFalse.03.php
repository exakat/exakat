<?php

$expected     = array('if(version_compare($version, $lower) >= 2)  /**/  else  /**/  ',
                     );

$expected_not = array('if(version_compare($version, $lower) >= 0) { /**/ } else { /**/ } ',
                      'if(version_compare($version, $lower) >= 1) { /**/ } else { /**/ }',
                     );

?>