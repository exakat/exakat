<?php

$expected     = array('C1 = a::C1',
                      'C2 = a::C2 + 2',
                      'D1 = a::D1 + 1',
                     );

$expected_not = array('c4 = a::C4',
                      'C3 = a::c3',
                      'D3 = a::d3 + 2',
                      'd4 = a::D4 + 3',
                      'E = 3',
                     );

?>