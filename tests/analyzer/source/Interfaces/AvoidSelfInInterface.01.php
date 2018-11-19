<?php

interface i extends ii {
    public const I = self::A + 2;
    public const I2 = 3;
    public const I3 = self::I2 + 3;
    public const I4 = parent::A + 2;
}

interface ii { 
    const IP = 5;
}

class x implements i {
//    const I = 3;
}


//echo x::I;
//echo I::I;
echo i::I2;
echo i::I3;
echo i::I4;

?>