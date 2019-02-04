<?php

$expected     = array('defined(\'_AKEEBA\') or die( )',
                      'if(stristr($_SERVER[\'REQUEST_URI\'], ".inc.php"))  /**/  ',
                     );

$expected_not = array('if(if (str_replace($_SERVER[\'REQUEST_URI\'], ".inc.php")))  /**/  ',
                     );

?>