<?php

$expected     = array('$fromXInUse1',
                      '$fromXInUse2',
                      '$onlyInCLosure',
                      '$onlyInCLosure',
                      '$seemsInBothClosuresButNot',
                      '$seemsInBothClosuresButNot',
                      '$fromXOnly');

$expected_not = array('$fromXOnlyTwice',
                      '$ca1',
                      '$ca2',
                      '$cb1',
                      '$cb2',
                      );

?>