<?php

$hs1 = '0x123';
$hs2 = ' 0x123';
$hs3 = " 0x123";
$hs4 = <<<HEREDOC
 0x123
HEREDOC;
$hs5 = <<<'NOWDOC'
     0x123f34
NOWDOC;

// $x is too late 
$hs6 = <<<HEREDOC
 0x123$x
HEREDOC;
$hs7 = " 0x123$x";


$nhs1 = 'n0x223';
$nhs2 = '0xf23';
$nhs3 = 'abcd';
$nhs4 = '0s755';
$nhs5 = " 0x2$n23";

// $x is early late 
$hs6 = <<<HEREDOC
{$x} 0x224
HEREDOC;

?>