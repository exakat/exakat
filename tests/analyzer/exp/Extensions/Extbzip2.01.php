<?php

$expected     = array('bzopen($filename, "w")',
                      'bzwrite($bz, $str)',
                      'bzclose($bz)',
                      'bzopen($filename, "r")',
                      'bzread($bz, 10)',
                      'bzread($bz)',
                      'bzclose($bz)',
                     );

$expected_not = array('bzclose($a)',
                     );

?>