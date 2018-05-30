<?php

$expected     = array('<<<HEREDOC
$x1->y1->z1
HEREDOC',
                      '<<<HEREDOC
$x2[y2]->z2
HEREDOC',
                      '<<<HEREDOC
$x3->y3[z3]
HEREDOC',
                      '<<<HEREDOC
$x4[y4][z4]
HEREDOC',
                      '<<<HEREDOC
$x5{y5}
HEREDOC',
                      '<<<HEREDOC
$x6{y5}
HEREDOC',
                     );

$expected_not = array('<<<HEREDOC
$X1->Y1->Z1
HEREDOC',
                      '<<<HEREDOC
$X2[Y2]->Z2
HEREDOC',
                      '<<<HEREDOC
$X3->Y3[Z3]
HEREDOC',
                      '<<<HEREDOC
$X4[Y4][Z4]
HEREDOC',
                      '<<<HEREDOC
$X5{Y5}
HEREDOC',
                      '<<<HEREDOC
$X6{Y5}
HEREDOC',
                     );

?>