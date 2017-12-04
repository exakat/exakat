<?php

$expected     = array('$fromXInUse1',
                      '$fromXInUse2',
                      '$onlyInCLosure',
                      '$onlyInCLosure',
                      '$seemsInBothClosuresButNot',
                      '$seemsInBothClosuresButNot',
                      '$ca1',
                      '$ca2',
                      '$cb1',
                      '$cb2',
                      '$fromXOnly',
                      '$fromXInUse1AndInCLosure',
                      '$fromXInUse2',
                      '$fromXInUse2AndInCLosure',
                      '$fromXInUse1',
                     );

$expected_not = array('$fromXOnlyTwice',
                     );

?>