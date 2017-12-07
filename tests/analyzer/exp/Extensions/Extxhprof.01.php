<?php

$expected     = array('xhprof_disable( )',
                      'xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY)',
                      'XHPROF_FLAGS_CPU',
                      'XHPROF_FLAGS_MEMORY',
                     );

$expected_not = array('xhprof_disable(2)',
                     );

?>