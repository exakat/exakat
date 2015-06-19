<?php
class x {
    const A = array(1, 2=>3);
}

print x::A[0];
print x::A[2][3];
print x::A[2][3][4  + 7];
?>