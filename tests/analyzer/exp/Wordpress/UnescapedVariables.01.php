<?php

$expected     = array('<?php echo $E',
                      '<?php echo $G',
                      'echo $a . \' b \' . $c');

$expected_not = array('<?php echo esc_html($f)',
                      '<?php echo esc_html($h)',
                      'echo esc_attr($x)'
                      );

?>