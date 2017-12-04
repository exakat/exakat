<?php

$expected     = array('<?= $G',
                      'print $a . \' b \' . $c',
                      '<?= ($e . $g)',
                     );

$expected_not = array('print(esc_html($x))',
                      'esc_html($e) . esc_xml($g)',
                     );

?>