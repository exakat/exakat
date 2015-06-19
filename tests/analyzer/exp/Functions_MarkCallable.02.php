<?php

$expected     = array("'x'",
                      "'Y', 'parent::x'",  
                      "'NoSuchClass', 'parent::x'",
                      "'NoParent', 'parent::x'",
                      "'Y2', 'parent::x3'",
                      "'Z2', 'parent::x3'",
                      "'Z2', 'parent::x3'",
                      "'Y2', 'parent::x3'",
                      "'X2', 'parent::x3'", 
                      "'X2', 'parent::x3'", 
                      "'Y2', 'x3'");

$expected_not = array(  // x doesn't exists
                     );

?>