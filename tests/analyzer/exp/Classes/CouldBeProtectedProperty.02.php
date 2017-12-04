<?php

$expected     = array('$aspublicButSBProtectedSelf',
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',
                      '$aspublicButSBProtectedSelf',
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',
                      '$aspublicButReally',
                      '$aspublicButReally2',
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