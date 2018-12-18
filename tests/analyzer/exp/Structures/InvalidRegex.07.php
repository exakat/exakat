<?php

$expected     = array('preg_replace_callback("/" . $regexp[C] . "/Z", array($this, \'method\'), $x)',
                      'preg_match($a->_regexDelimiter . \'^\' . $a->g[$d] . \'$\' . $a->f . \'iu\', $d)',
                      'preg_match($this->F( ) . \'D\', $FF)',
                     );

$expected_not = array('preg_match($a->_regexDelimiter . \'^\' . $a->g[$d] . \'$\' . $a->f . \'iu\', $d)',
                      'preg_match_all("/<($b)" . X . "(>(.*)<\\/$b>|(\\/)?>)/siU", $string, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);',
                     );

?>