<?php

$expected     = array('mail(\'someone@domaine.tld\', \'title\', \'message\')',
                     );

$expected_not = array('$a[3]',
                      '$b->c->d',
                      '$b->c->d()',
                     );

?>