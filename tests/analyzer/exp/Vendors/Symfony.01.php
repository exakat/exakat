<?php

$expected     = array('Symfony\\Component\\HttpFoundation\\Response',
                      'Sensio\\Bundle\\FrameworkExtraBundle\\Configuration\\Route',
                      'Response(\'<html><body>Lucky number: \' . $number . \'</body></html>\')',
                     );

$expected_not = array('Response',
                     );

?>