<?php

file_put_contents('/tmp/a.txt', $a);
file_put_contents("/tmp/$a.txt", $a);
file_put_contents('/tmp/'.PATH.'.txt', $a);
file_put_contents('/tmp'.PATH.'.txt', $a);
file_put_contents('/tmpFolder'.$a.'.txt', $a);
file_put_contents(tmpFolder.$a.'.txt', $a);

mkdir('C:\WINDOWS\TEMP\a.txt', $a);
mkdir('C:\WINDOWS\b.txt', $a);
fopen('D:\WINDOWS\TEMP\a.txt', $a);
fopen('D:\WINDOWS\b.txt', $a);

rmdir("C:\WINDOWS\\".A.".txt", $a);
rmdir("C:\WINDOWS\\TEMP\\".B.".txt", $a);

glob("C:\USERS\\", $a);

?>