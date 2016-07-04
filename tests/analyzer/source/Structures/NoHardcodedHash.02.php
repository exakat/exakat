<?php

$a = '$1$rasmusle$rISCgZzpwk3UhDidwXvin0';
//Blowfish:     
$a = '$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi';
//SHA-256:      
$a = '$5$rounds=5000$usesomesillystri$KqJWpanXZHKq2BOB43TSaYhEWsQ1Lr5QNyPCDH/Tp.6';
//SHA-512:      
$a = '$6$rounds=5000$usesomesillystri$D4IrlXatmP7rx3P3InaxBeoomnAihCKRVQP22JZ6EY47Wc6BkroIuUUBOov1i.S5KPgErtP/EN5mcO.ChWQW21';

// NOT a hash, because of 3
$a = '$3$rasmusle$rISCgZzpwk3UhDidwXvin0';

// NOT found, because concatenation
$a = "\$1\$asdlfjasdf";

?>