<?php


while($line = fgets($fp1) != 'a') {}
while(fgets($fp2) != 'a') {}
while(fgets($fp3) != 'a') {}
while('a' != fgets($fp4)) {}
while(1 != fgets($fp5)) {}
while(0 != fgets($fp6)) {}

?>