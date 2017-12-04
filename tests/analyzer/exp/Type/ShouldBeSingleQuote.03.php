<?php

$expected     = array('"abc"',
                     );

$expected_not = array('"\\043"',
                      '"\\x23"',
                      '"\\u{1f418}"',
                     );

?>