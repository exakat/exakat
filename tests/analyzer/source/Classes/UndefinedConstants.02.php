<?php

class x1 { const c1 = 1; }
class x2 extends x1 { const c2 = 2; }
class x3 extends x2 { const c3 = 3; }
class x4 extends x3 { 
    const c4 = 4;
    
    function x() {
        self::c5;
        self::c4;
        self::c3;
        self::c2;
        self::c1;
        
        static::c5;
        static::c4;
        static::c3;
        static::c2;
        static::c1;
        
        parent::c5;
        parent::c4;
        parent::c3;
        parent::c2;
        parent::c1;
    }
 }

interface i1 { const ic1 = 5; }
interface i2 extends i1 { const ic2 = 6; }
interface i3 extends i2 { const ic3 = 7; }

class x53 implements i3 {}
class x52 implements i2 {}
class x51 implements i1 {}
class x5123 implements i1, i2, i3 {}

x4::c5;
x4::c4;
x4::c3;
x4::c2;
x4::c1;

x53::ic1;
x53::ic2;
x53::ic3;

x52::ic1;
x52::ic2;
x52::ic3;

x51::ic1;
x51::ic2;
x51::ic3;

?>