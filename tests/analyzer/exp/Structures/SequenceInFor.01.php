<?php

$expected     = array('for($a = 1 ;  /**/  ; $a++) { /**/ } ',
                      'for($a = 1 ; $d !== false ;  /**/ ) { /**/ } ',
                      'for( /**/  ;  /**/  ;  /**/ ) { /**/ } ',
                      'for( /**/  ; $d !== false ; $e++) { /**/ } ',
                     );

$expected_not = array('for ($a = 1; $b < 2; $c++) { /**/ } ',
                     );

?>