<?php

$expected     = array('empty($a->optionalEmptyA)',
                      'empty($b->optionalEmptyB)',
                      'empty($c->optionalEmptyC)',
                     );

$expected_not = array('empty($a->nonEmptyA)',
                      'empty($b->nonEmptyB)',
                      'empty($c->nonEmptyC)',
                     );

?>