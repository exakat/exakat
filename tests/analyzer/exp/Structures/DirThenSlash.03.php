<?php

$expected     = array('\'a\' . __DIR__ . WITHOUT_SLASH',
                      '__DIR__ . WITHOUT_SLASH',
                     );

$expected_not = array('\'a\' . __DIR__ . WITH_SLASH',
                      '__DIR__ . WITH_SLASH',
                     );

?>