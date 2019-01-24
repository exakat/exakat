<?php

$expected     = array('$a ??= \'value\'',
                     );

$expected_not = array('$a = $a ?? \'value\'',
                      '$a ?? \'value\'',
                     );

?>