<?php

$expected     = array('unlink(\'/tmp/x.txt\')',
                      'file_put_contents(\'/tmp/x.txt\', \'a\')',
                     );

$expected_not = array('unlink(\'/tmp/method.txt\')',
                     );

?>