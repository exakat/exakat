<?php

$expected     = array('$x .= \'e\'',
                      '$x .= \'d\'',
                      '$x .= A',
                      '$x .= \\B',
                      '$x .= \\C',
                      '$x .= <<<\'X\'
    
X',
                      '$x .= <<<X
    
X',
                      '$x .= D',
                     );

$expected_not = array('$x .= foo($a)',
                     );

?>