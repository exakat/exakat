<?php

$files = glob('exp/*.txt');

shuffle($files);

$remove = array_slice($files, 0, 100);

foreach($remove as $r) {
    print "$r\n";
    unlink ($r);
}

?>