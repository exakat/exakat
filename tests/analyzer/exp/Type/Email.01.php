<?php

$expected     = array('"mail@server.org"',
                      '"other.mail@this.server.org"',
                     );

$expected_not = array('no@email',
                      '"$@c.to "',
                      '">@c.to "',
                      '">d@c.to "',
                      '"\\@c.to "',
                      '"a+@c.to "',
                      '"a+@c.to "',
                     );

?>