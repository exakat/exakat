<?php

$expected     = array('$aspublicButSBPrivateFull = 9',
                      '$apublicButSBPrivate = 2',
                      '$aspublicButSBPrivateStatic = 8',
                      '$aspublicButSBPrivateSelf = 7',
                      '$aprotected = 5',
                      '$asprotected = 12',
                     );

$expected_not = array('$apublicButReally = 3',
                      '$apublicButReally2 = 4',
                      '$asprivate = 6',
                      '$aprivate = 13',
                     );

?>