<?php
class x {
    const A = array(1, 2=>3);
}

print x::A[4.3  + 7];
print x::A[2][3][$x  + 7];
print x::A[2][3][null  + 7];
print x::A[2][3][true  + 7];
?>