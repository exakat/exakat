<?php

$before = 1;

class a {
    var              $p1;
    public           $p2;
    protected        $p3;
    private          $p4;
    public    static $p5;
    protected static $p6;
    private   static $p7;

    var              $p11 = 1;
    public           $p12 = 2;
    protected        $p13 = 3;
    private          $p14 = 4;
    public    static $p15 = 5;
    protected static $p16 = 6;
    private   static $p17 = 7;
    
    private function method_private() {}
}

$after = 2;

?>