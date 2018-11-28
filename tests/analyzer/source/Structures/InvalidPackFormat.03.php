<?php
pack('vC2v4C12@32', 32, 0xDA, 0x27, 20, 0, 0, 2, 0x52, 0x65, 0x73, 0x42, 1, 2, 0, 0, 1, 4, 0, 0);
unpack('@1/H*bo/Hbi', 'abcdefghijklm');
unpack('@5/C*', 'abcdefghijklm');
unpack('@/5/C*', 'abcdefghijklm');
unpack("N$numberOfLongs", $paddedBytes);
?>