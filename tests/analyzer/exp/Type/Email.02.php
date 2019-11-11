<?php

$expected     = array('<<<EMAIL
mail@server.org
EMAIL',
                      'C . "@this.server.org"',
                      '"othermail" . "@this.server.org"',
                     );

$expected_not = array('"no@email"',
                      'mail@server.org',
                     );

?>