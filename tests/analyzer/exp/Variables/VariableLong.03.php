<?php

$expected     = array('$abcdefghijklmnopqrst',
                      '$abcdefghijklmnopqrstu',
                      '$abcdefghijklmnopqrstuv',
                      '$abcdefghijklmnopqrstuvw',
                      '$abcdefghijklmnopqrstuvwx',
                      '$abcdefghijklmnopqrstuvwxy',
                      '$abcdefghijklmnopqrstuvwxyz',
                     );

$expected_not = array('$a',
                      '$ab',
                      '$abc',
                      '$abcd',
                      '$abcde',
                      '$abcdef',
                      '$abcdefg',
                      '$abcdefgh',
                      '$abcdefghi',
                      '$abcdefghij',
                      '$abcdefghijk',
                      '$abcdefghijkl',
                      '$abcdefghijklm',
                      '$abcdefghijklmn',
                      '$abcdefghijklmno',
                      '$abcdefghijklmnop',
                      '$abcdefghijklmnopq',
                      '$abcdefghijklmnopqr',
                      '$abcdefghijklmnopqrs',
                     );

?>