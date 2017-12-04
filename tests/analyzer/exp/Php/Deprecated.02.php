<?php

$expected     = array('split(\':\', \'global namespace\')',
                      'spliti(\':\', \'namespace B but fallback\')',
                      'eregi(\':\', \'namespace B but fallback\')',
                      'call_user_method_array(\':\', \'Namespace C but fallback\')',
                     );

$expected_not = array('SPLITI(\':\', \'Namespace C\')',
                      'SPLITI(\':\', \'Namespace C\')',
                      'explode(\':\', \'global namespace\')',
                      'dl(\'global namespace\')',
                     );

?>