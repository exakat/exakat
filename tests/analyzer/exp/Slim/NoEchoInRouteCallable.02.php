<?php

$expected     = array('$app->get(\'/abde/\', Xwithecho::class)',
                     );

$expected_not = array('$app->get(\'/abd/\', X::class)',
                     );

?>