<?php

$expected     = array('$d = "e" . $f . ""',
                      '<?= $K',
                      'echo $j',
                     );

$expected_not = array('echo $g',
                      'echo $h',
                      'echo $hi',
                     );

?>