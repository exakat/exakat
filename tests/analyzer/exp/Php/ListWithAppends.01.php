<?php

$expected     = array('list($a[], $a[], $a[])',
                      'list($b[], $c[], $b[], $c[], $b[])',
                     );

$expected_not = array('list($a1, $a2, $a3)',
                      'list($a[], $b[], $c[])',
                      'list($a1[], $a2, $a3)',
                     );

?>