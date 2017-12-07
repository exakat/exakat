<?php

$expected     = array('<?= strtolower($h)',
                      'echo $a . \' b \' . $c',
                      '<?= $G',
                      '<?= $E',
                     );

$expected_not = array('<?= esc_html($f)',
                     );

?>