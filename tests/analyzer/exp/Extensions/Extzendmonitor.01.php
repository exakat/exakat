<?php

$expected     = array('zend_monitor_set_aggregation_hint(get_class($obj) . \': \' . $e->getMessage( ))',
                      'zend_monitor_custom_event(\'Failed Job\', $e->getMessage( ))',
                     );

$expected_not = array(
                     );

?>