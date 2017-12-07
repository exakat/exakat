<?php

$expected     = array('abstract class UnusedAbstraction { /**/ } ',
                      'class UsingAbstraction extends UsedAbstraction { /**/ } ',
                     );

$expected_not = array('abstract class UsedAbstraction { /**/ } ',
                     );

?>