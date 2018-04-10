<?php

$expected     = array( '$b != 3 ? \FALSE : \TRUE', 
                       '$a != 3 ? \TRUE : \FALSE', 
                       '$b != 2 ? FALSE : TRUE', 
                       '$a != 2 ? TRUE : FALSE', 
                       '$b == 2 ? false : true', 
                       '$a == 2 ? true : false'
                     );

$expected_not = array('$c == 2 ? false : -1',
                     );

?>