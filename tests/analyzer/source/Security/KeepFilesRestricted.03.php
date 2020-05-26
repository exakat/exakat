<?php

chmod($f, 777);
chmod($f, 0777);
chmod($f, 511);
chmod($f, 0x777);
chmod($f, 0700);
chmod($f, 0b111111111);
chmod($f, -1);
chmod($f, 0);

?>