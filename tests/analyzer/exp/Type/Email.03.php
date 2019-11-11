<?php

$expected     = array('<<<\'NOEMAIL\'
mail2@server.org $y
NOEMAIL',
                      '"mail3$x@server.org"',
                      '"mail@server.org$x"',
                     );

$expected_not = array('mail2@server.org $y',
                      'mail@server.org',
                     );

?>