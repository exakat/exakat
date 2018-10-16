<?php

pack("nvc*", 0x1234, 0x5678, 65, 66);
$binarydata = "\x04\x00\xa0\x00";
$array = unpack("cchars/nint", $binarydata);
repack("nvc*", 0x1234, 0x5678, 65, 66);
unpack($format, 0x1234, 0x5678, 65, 66);


?>