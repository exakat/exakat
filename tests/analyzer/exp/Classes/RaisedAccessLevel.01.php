<?php

$expected     = array('$publicThenProtected',
                      '$protectedThenPrivate',
                      '$publicThenPrivate',
                     );

$expected_not = array('$privateOnly',
                      '$privateThenPrivate',
                      '$privateThenProtected',
                      '$privateThenPublic',
                      '$protectedOnly',
                      '$protectedThenProtected',
                      '$protectedThenPublic',
                      '$publicOnly',
                      '$publicThenPublic',
                     );

?>