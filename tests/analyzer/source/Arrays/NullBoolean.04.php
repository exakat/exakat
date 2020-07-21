<?php

const A = 1;
const B = 2.2;
const X = '3';

echo A[1];
echo B[2];
echo X[2];

echo 'null'[3];

echo x::XPu[1];
echo x::XPuArr[1];
echo x::XPString[1];

class x {
    public const XPu = 3;
    public const XPuArr = 4.4;
    public const XPString = "4.5";
}
?>