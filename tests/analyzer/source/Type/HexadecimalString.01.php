<?php

$hs1 = '0x123';
$hs2 = ' 0x124';
$hs3 = " 0x125";
$hs4 = <<<HEREDOC
 0x126
HEREDOC;
$hs5 = <<<'NOWDOC'
     0x127f34
NOWDOC;

// $x is too late 
$hs6 = <<<HEREDOC
 0x128$x
HEREDOC;
$hs7 = " 0x129$x";
$hs8 = " 0x12G9$x";
$hs9 = " 0x2$n23";


$nhs1 = 'n0x223';
$nhs2 = '0xf23';
$nhs3 = 'abcd';
$nhs4 = '0s755';

// $x is early late 
$hs6 = <<<HEREDOC
{$x} 0x224
HEREDOC;

?>