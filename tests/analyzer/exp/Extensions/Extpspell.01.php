<?php

$expected     = array('pspell_new("en")',
                      'pspell_check($pspell_link, "testt")',
                     );

$expected_not = array('echo "Sorry, wrong spelling"',
                     );

?>