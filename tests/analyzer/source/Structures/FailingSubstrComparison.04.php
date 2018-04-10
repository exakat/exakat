<?php

$a = 'abcde';
substr($a, 0, 3) == 'abcd';
substr($a, 0, 3) == 'abc';
substr($a, 0, -2) == 'abcde';

?>