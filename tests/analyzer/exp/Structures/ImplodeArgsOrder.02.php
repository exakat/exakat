<?php

$expected     = array('implode(AR, $theTable)',
                      'implode(\', \', $returnS)',
                      'implode(\', \', $return)', 
                      'implode(\', \', $returnStatic)',
                     );

$expected_not = array('implode($theTable, AR)',
                     );

?>