<?php

$expected     = array('htmlentities($miss_2)',
                      'htmlentities($miss_1_wrong_2, E_ALL)',
                      'htmlentities($miss_1, ENT_QUOTES)',
                      'htmlentities($wrong_3, ENT_QUOTES, \'UTF9\')');

$expected_not = array();

?>