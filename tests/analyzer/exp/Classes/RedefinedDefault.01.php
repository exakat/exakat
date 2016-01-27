<?php

$expected     = array('$redefined2 = 1',
                      '$redefined = 1',
);

$expected_not = array('$Notredefined = 1',  
                      '$redefinedInAnotherMethod = 1',
                      '$updated = 1');

?>