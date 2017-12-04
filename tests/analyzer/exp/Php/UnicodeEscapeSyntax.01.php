<?php

$expected     = array('"\\u{0000aa}"',
                      '"\\u{aa}"',
                      '"\\u{0}"',
                      '"\\u{9999}"',
                     );

$expected_not = array('"\\U{9999}"',
                     );

?>