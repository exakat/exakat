<?php

interface i1 { const ci1 = 1; }
interface i2 { const ci2 = 1; }
interface i3 { const ci3 = 1; }
interface i4 { const ci4 = 1; }

class x implements i1, i2, i3, i4 {}

print x::ci1;
print x::ci2;
print x::ci3;
print x::ci4;
print x::ci5;

?>