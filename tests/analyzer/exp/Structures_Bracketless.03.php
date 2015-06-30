<?php

$expected     = array('if (1)  /**/ ', 
                      'if (3)  /**/  else  /**/ ');

$expected_not = array('elseif (6) ;',
                      'elseif (8) ;', 
                      'if (4) ; else elseif (5) ; else elseif (6) ;', 
                      'elseif (5) ; else elseif (6) ;');

?>