<?php

echo null[1];
echo \null[1];
echo true[1];
echo \false[1];

const A = null;
const B = true;

echo A[1];
echo B[2];

echo 'null'[3];

echo x::XPu[1];
echo x::XPuArr[1];

class x {
    public const XPu = null;
    public const XPuArr = [];
}
?>