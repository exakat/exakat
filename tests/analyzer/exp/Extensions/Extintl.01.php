<?php

$expected     = array('collator_create(\'en_US\')',
                      'collator_compare($coll, "string#1", "string#2")',
                     );

$expected_not = array('collator_ksort(\'en_US\')',
                     );

?>