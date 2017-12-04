<?php

$expected     = array('$r->getConstant($constante)',
                      'constant("Classe::constante")',
                      'constant("Classe::$constante")',
                     );

$expected_not = array('constant("constante")',
                      'constant("$classConstante")',
                     );

?>