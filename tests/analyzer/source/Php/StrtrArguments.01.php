<?php

strtr('abc', 'abc', 'ABC');
strtr('abc', 'abc', 'ABCE');
strtr('abc', 'abcd', 'ABC');
strtr('abc', 'abcd', '');
$a->strtr('abc', 'abcde', '');

?>