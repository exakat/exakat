<?php

$expected     = array('$asprotectedReallyFull',
                      '$asprotectedReallyStatic',
                      '$asprotectedReallyChildren',
                      '$asprotectedReallySelf',
                      '$aprotectedButSBPrivate',
                      '$aprotectedButSBPrivate2',
                      '$aspublic',
                      '$asprotectedButSBPrivateChildren', 
                      '$asprotectedButSBPrivateFull',
                     );

$expected_not = array('$aprivate',
                      '$aprotectedButSBPrivate3',
                      '$aprotectedButSBPrivate4',
                     );

?>