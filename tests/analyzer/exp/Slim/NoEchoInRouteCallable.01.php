<?php

$expected     = array('$app->get(\'/abd/\', function ($echo) { /**/ } )',
                     );

$expected_not = array('$app->get(\'/abd/\', function ($withEcho) { /**/ } )',
                     );

?>