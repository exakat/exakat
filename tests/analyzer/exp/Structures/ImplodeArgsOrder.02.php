<?php

$expected     = array('implode(AR, $theTable)',
                      'implode(\', \', $returnS)',
                     );

$expected_not = array('implode($theTable, AR)',
                      'implode(\', \', $return)',
                      'implode(\', \', $returnStatic)'
                     );

?>