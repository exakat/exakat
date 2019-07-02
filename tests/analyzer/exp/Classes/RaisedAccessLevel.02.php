<?php

$expected     = array('$publicThenProtected',
                      '$publicThenProtected',
                      '$publicThenPrivate',
                      '$protectedThenPrivate',
                      '$protectedThenPrivate',
                      '$publicThenPrivate',
                     );

$expected_not = array('$privateOnly = 1',
                      '$privateThenPrivate = 12',
                      '$privateThenProtected = 13',
                      '$privateThenPublic = 14',
                      '$protectedOnly =15',
                      '$protectedThenProtected = 17',
                      '$protectedThenPublic = 18',
                      '$publicOnly = 19',
                      '$publicThenPublic 22',
                     );

?>