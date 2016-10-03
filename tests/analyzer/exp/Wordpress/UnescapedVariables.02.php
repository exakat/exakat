<?php

$expected     = array('<?php echo $G', 
                      'print $a . \' b \' . $c',
                      '<?php echo ($e . $g)');

$expected_not = array('print(esc_html($x))',
                      'esc_html($e) . esc_xml($g)');

?>