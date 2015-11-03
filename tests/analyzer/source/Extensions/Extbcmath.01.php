<?php
//bcpi function with Gauss-Legendre algorithm
//by Chao Xu (Mgccl)
function bcpi($precision){
    $limit = ceil(log($precision)/log(2))-1;
    bcscale($precision+6);
    $a = 1;
    $b = bcdiv(1,bcsqrt(2));
    $t = 1/4;
    $p = 1;
    while($n < $limit){
        $x = bcdiv(bcadd($a,$b),2);
        $y = bcsqrt(bcmul($a, $b));
        $t = bcsub($t, bcmul($p,bcpow(bcsub($a,$x),2)));
        $a = $x;
        $b = $y;
        $p = bcmul(2,$p);
        ++$n;
    }
    return bcdiv(bcpow(bcadd($a, $b),2),bcmul(4,$t),$precision);
}
?>