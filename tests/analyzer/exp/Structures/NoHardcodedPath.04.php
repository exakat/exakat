<?php

$expected     = array('glob(\'/views/$a/ab.php\')',
                     );

$expected_not = array('glob("*/*/views/*.php")',
                      'glob(\'*/*/views/ab?.php\')',
                      'glob("/views/a*b?.php")',
                     );

?>