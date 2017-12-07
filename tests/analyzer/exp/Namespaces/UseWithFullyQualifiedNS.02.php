<?php

$expected     = array('\\Fully\\Qualified\\NS',
                      '\\FullyQualifiedNs',
                     );

$expected_not = array('\\Fully\\Qualified\\aClass\\NS',
                      '\\FullyQualifiedClassNs',
                      '\\Fully\\Qualified\\aTrait\\NS',
                      '\\FullyQualifiedTraitNs',
                      'A',
                      '\\B',
                     );

?>