<?php
    class x {
        static public function sm( ) { echo __METHOD__."\n"; }
        public function smx( ) { echo __METHOD__."\n"; }
    } 
    
    x::sm( ); // echo x::sm 
    
    // Dynamic call
    ['x', 'sm']();
    [\x::class, 'sm']();

    ['x', 'smx']();
    [\x::class, 'smx']();

    $s = 'x::smx';
    $s();
    $s2 = 'x::sm';
    $s2();

?>
