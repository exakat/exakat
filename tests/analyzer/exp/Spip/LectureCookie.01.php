<?php

$expected     = array('$_COOKIE[\'read1\']',
                      '$_COOKIE[\'read21\'][\'read22\']',
                      '$_COOKIE[\'read31\'][\'read32\'][\'read33\']',
                      '$_COOKIE[\'read4\']',
                      '$_COOKIE',
);

$expected_not = array('$_COOKIE[\'read21\']',
                      '$_COOKIE[\'inside_recuperer_cookies_spip\']',
                      '$_COOKIE',);

?>