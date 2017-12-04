<?php

$expected     = array('pathinfo($fileF, PATHINFO_FILENAME | 3)',
                      'pathinfo($fileA, PATHINFO_DIRNAME | PATHINFO_BASENAME)',
                      'pathinfo($fileF, PATHINFO_FILENAME2)',
                     );

$expected_not = array('pathinfo($fileD, PATHINFO_FILENAME)',
                     );

?>