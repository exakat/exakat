<?php

$expected     = array('parent $parentClosure',
                      'self $selfClosure',
                     );

$expected_not = array('parent $parentMethod',
                      'self $selfMethod',
                      '$aParent instanceof parent',
                      '$aSelf instanceof self',
                      '$aStatic instanceof static',
                     );

?>