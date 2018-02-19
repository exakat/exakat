<?php


do { ++$b; } while($line = fgets($fp1) != 'a');
do { ++$b; } while(fgets($fp2) != 'a');
do { ++$b; } while(fgets($fp3) != 'a');
do { ++$b; } while('a' != fgets($fp4));
do { ++$b; } while(1 != fgets($fp5));
do { ++$b; } while(0 != fgets($fp6));

?>