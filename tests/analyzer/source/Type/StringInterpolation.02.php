<?php

$a = <<<HEREDOC
$x1->y1->z1
HEREDOC;

$b = <<<HEREDOC
$x2[y2]->z2
HEREDOC;

$c = <<<HEREDOC
$x3->y3[z3]
HEREDOC;

$d = <<<HEREDOC
$x4[y4][z4]
HEREDOC;

$e = <<<HEREDOC
$x5{y5}
HEREDOC;

$f = <<<HEREDOC
$x6{y5}
HEREDOC;


$A = <<<HEREDOC
{$X1->Y1}->Z1
HEREDOC;

$B = <<<HEREDOC
{$X2[Y2]}->Z2
HEREDOC;

$C = <<<HEREDOC
{$X3->Y3}[Z3]
HEREDOC;

$D = <<<HEREDOC
{$X4[Y4]}[Z4]
HEREDOC;

$E = <<<HEREDOC
{$X5}{Y5}
HEREDOC;

$F = <<<HEREDOC
{$X6}{Y5}
HEREDOC;


?>