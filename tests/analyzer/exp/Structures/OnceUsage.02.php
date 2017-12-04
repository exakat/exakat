<?php

$expected     = array('require_once CONSTANTE',
                      'include_once \'include.php\' or die ',
                     );

$expected_not = array('include $include;',
                     );

?>