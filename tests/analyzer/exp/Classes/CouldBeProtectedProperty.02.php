<?php

$expected     = array('$aspublicButSBProtectedSelf',
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',  // line 6
                      '$apublicButSBProtected',  // line 25
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