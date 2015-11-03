<?php

const EXTERNAL = 1;

class x {
    const YES = 1;
    
    private   $noneProperty;
    protected $cseProperty  = 3 + 4;
    public    $ncse         = self::YES;
    static    $ncse2        = self::YES * 3;

    public    $pu1 = 1 / 3, $pu2 = 2 - 4, $pu3 = EXTERNAL - 5;
    
}
?>
