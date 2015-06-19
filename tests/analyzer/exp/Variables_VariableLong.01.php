<?php

$expected     = array (
  1 => '$abcdefghijklmnopqrst',
  2 => '$abcdefghijklmnopqrstu',
  3 => '$abcdefghijklmnopqrstuv',
  4 => '$abcdefghijklmnopqrstuvw',
  5 => '$abcdefghijklmnopqrstuvwx',
  6 => '$abcdefghijklmnopqrstuvwxy',
  7 => '$abcdefghijklmnopqrstuvwxyz',
);

$expected_not = array (
  0 => '$a',
  1 => '$ab',
  2 => '$abc',
  3 => '$abcd',
  4 => '$abcde',
  5 => '$abcdef',
  6 => '$abcdefg',
  7 => '$abcdefgh',
  8 => '$abcdefghi',
  9 => '$abcdefghij',
  10 => '$abcdefghijk',
  11 => '$abcdefghijkl',
  12 => '$abcdefghijklm',
  13 => '$abcdefghijklmn',
  14 => '$abcdefghijklmno',
  15 => '$abcdefghijklmnop',
  16 => '$abcdefghijklmnopq',
  17 => '$abcdefghijklmnopqr',
  18 => '$abcdefghijklmnopqrs',
);

?>