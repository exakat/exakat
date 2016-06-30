<?php

$expected     = array('$asprotectedReallyFull',
                      '$asprotectedReallyStatic',
                      '$asprotectedReallyChildren',
                      '$asprotectedReallySelf',
                      '$aprotectedButSBPrivate',
                      '$aprotectedButSBPrivate2',
);

$expected_not = array('$aprivate',
                      '$aprotectedButSBPrivate3',
                      '$aprotectedButSBPrivate4',
                      );

?>