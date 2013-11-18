<?php

class a {
    public    $p1;
    private   $p2;
    protected $p3;
    
    static $p4;
    static $p10 = 1;
    
    var $p5;
    
    public    $p6 = 2;

    public static $p7 = 2;
    private static $p8 = 2;
    protected static $p9 = 2;
}

$v1 = 1;
$$v2 = 2;
${$v3.$v4} == 3;

?>