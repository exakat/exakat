<?php

use function pack as repack;

function foo2(    $b = "ccc*") {
    $a = "nnnvc*";
    pack($a, 0x1234, 0x5678, 65, 66);
    pack($b, 0x1234, 0x5678, 65, 66);
}

$binarydata = "\x04\x00\xa0\x00";
$array = unpack("cchars/nint", $binarydata);

const A = "ncv*";
repack(A, 0x1234, 0x5678, 65, 66);

class x {
    const Y = "cnv*";
}
unpack(x::Y, 0x1234, 0x5678, 65, 66);

function foo() : string {
    if (rand(0, 1) % 2) {
        return 'A';
    } elseif (rand(0, 1) % 2) {
        return $c;
    } else {
        return 'B';
    }
}

pack(foo(), 0x1234, 0x5678, 65, 66);

?>