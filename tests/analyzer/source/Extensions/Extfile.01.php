<?php

file_put_contents('/tmp/x.txt', 'a');
unlink('/tmp/x.txt');

$a->unlink('/tmp/method.txt');

?>