<?php

$expected     = array('elseif ($c) : ; else : ; endif', 
                      'if ($a) : ; else : elseif ($b) :  /**/  else : elseif ($c) : ; else : ; endif endif endif', 
                      'elseif ($b) :  /**/  else : elseif ($c) : ; else : ; endif endif');

$expected_not = array();

?>