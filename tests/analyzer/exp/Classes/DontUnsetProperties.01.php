<?php

$expected     = array('(unset) $this->b',
                      'unset($b->a)',
                      'unset($this->a)',
                     );

$expected_not = array('unset($this->a[\'d\'])',
                      '',
                     );

?>