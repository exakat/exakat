<?php

interface i {
    const A = 1;
}

class x {
    private const XP =1;

}
class y extends x implements i {
    public const XP = 3;
}

echo i::A;
echo x::XP;
echo y::XP;

echo Z::A; // Just unknown

?>