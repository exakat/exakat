<?php

$expected     = array('$aspublicButSBProtectedSelf',
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',
                      '$apublicButSBProtected',
                     );

$expected_not = array('$asprotected',
                      '$asprivate',
                      '$aspublicButReally',
                      '$aspublicButReally2',
                      '$aprotected',
                      '$aprivate',
                      '$apublicButReally',
                      '$apublicButReally2',
                     );

?>