<?php

$expected     = array('<<<UES
\a\b\u{
UES
',
                      '<<<\'UES\'
\u{ab$c
UES',
                      '"\u{0000aa}"',
                      '"\u{9999}"',
                      "'\u{'",
                      '"\u{aa}"',
                      '"\u{"',
);

$expected_not = array('\U{');

?>