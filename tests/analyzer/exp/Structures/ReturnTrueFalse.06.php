<?php

$expected     = array('if(version_compare($version, $upper) <= 0) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(version_compare($version, $lower) >= 0) { /**/ } else { /**/ } ',
                     );

?>