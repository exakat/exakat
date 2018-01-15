<?php

$expected     = array('unset($value)',
                      'unset($valuep)',
                      'unset($valuep2)',
                      'unset($valuek)',
                      'unset($theStatic)',
                      'unset($theGlobal)',
                      'unset($argByReference)',
                      'unset($argByValue)',
                      'unset($valuep->property)',
                      'unset($valuep2->property)',
                     );

$expected_not = array('unset($valuep2->property->property2)',
                      'unset($valuep2->property->property2)',
                     );

?>