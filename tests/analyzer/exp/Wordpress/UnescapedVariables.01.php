<?php

$expected     = array('<?= $E',
                      '<?= $G',
                      'echo $a . \' b \' . $c',
                     );

$expected_not = array('<?php echo esc_html($f)',
                      '<?php echo esc_html($h)',
                      'echo esc_attr($x)',
                     );

?>