<?php

$expected     = array('\'x\'',
                      'array($this, \'xy\')',
                      'array($a, \'x2\')',
                      'array(\'Z2\', \'parent::x3\')',
                      'array(\'X2\', \'parent::x3\')',
                      'array(\'Y2\', \'x3\')',
                      'array(\'Y2\', \'parent::x3\')',
                      'array(\'Y\', \'parent::x\')',
                      'array(\'NoParent\', \'parent::x\')',
                      'array(\'NoSuchClass\', \'parent::x\')',
                      'array(\'Y2\', \'parent::x3\')',
                      'array(\'X2\', \'parent::x3\')',
                      'array(\'Z2\', \'parent::x3\')',
                     );

$expected_not = array('array(\'Y2\', \'parent2::x2\')',
                     );

?>