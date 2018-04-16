<?php

echo strtr('abc', 'abc', "AB\u{00a5}");
echo strtr('abc', 'abc', "AB\090");
echo strtr('abc', 'abc', "AB\t");
echo strtr('abc', 'ab', "AB\t");
echo strtr('abc', 'abcd', "AB\t");

?>