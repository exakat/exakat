<?php

$expected     = array('png2wbmp($image)',
                      'jpeg2wbmp($image)',
                     );

$expected_not = array('strlen($a)',
                      '$a->png2wbmp($method)',
                      'A::jpeg2wbmp($static)',
                     );

?>