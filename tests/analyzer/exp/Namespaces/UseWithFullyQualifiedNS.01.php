<?php

$expected     = array('\\A\\B\\FullyQualifiedAlias',
                      '\\FullyQualifiedNs',
                      '\\Fully\\Qualified\\NS',
                      '\\Fully\\Qualified\\NS3Alias',
                      '\\Fully\\Qualified\\NS2Alias',
                      '\\Fully\\Qualified\\NS1Alias',
                      '\\Fully\\Qualified\\NS3',
                      '\\Fully\\Qualified\\NS2',
                      '\\Fully\\Qualified\\NS1',
                     );

$expected_not = array('Qualified\NS4',
                      'QualifiedAlias as QualifiedAlias',
                     );

?>