<?php

$expected     = array('$global',
                      '$global2',
                      '$global',
                     );

$expected_not = array('$functionVar',
                      '$closureVar',
                      '$classVar',
                      '$methodVar',
                      '$interfaceVar',
                      '$traitVar',
                      '$traitMethodVar',
                     );

?>