<?php

$expected     = array('rmdir("C:\\WINDOWS\\\\" . A . ".txt", $a)',
                      'file_put_contents(\'/tmp/\' . PATH . \'.txt\', $a)',
                      'mkdir(\'C:\\WINDOWS\\TEMP\\a.txt\', $a)',
                      'file_put_contents(\'/tmp/a.txt\', $a)',
                      'mkdir(\'C:\\WINDOWS\\b.txt\', $a)',
                      'rmdir("C:\\WINDOWS\\\\TEMP\\\\" . B . ".txt", $a)',
                      'file_put_contents("/tmp/$a.txt", $a)',
                     );

$expected_not = array('file_put_contents(tmpFolder.$a.\'.txt\', $a)',
                      'file_put_contents(\'/tmpFolder\'.$a.\'.txt\', $a)',
                     );

?>