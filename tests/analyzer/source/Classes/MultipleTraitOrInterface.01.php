<?php

use tAliased as Ta1, tAliased as Ta2, tAliased as Ta3;
use iAliased as I1, iAliased as I2, iAliased as I3;

trait t1 {}
trait t3 {}
trait t4 {}
trait tAliased {}

class x {
    use t3,t3,t3;
}

class x4 {
    use Ta1, Ta2, Ta3;
}

class x5 {
    use t4;
    use t4;
    use t4;
    use t4;
    
    use t1;
}

class x1 implements i,i,i {}
class x2 implements I1, I2, I3 {}
class x3 implements I1, i, ArrayAccess {}


?>