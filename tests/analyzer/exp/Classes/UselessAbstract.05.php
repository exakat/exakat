<?php

$expected     = array('abstract class NoAbstractMethods { /**/ } ',
                     );

$expected_not = array('abstract class OneMethod { /**/ } ',
                      'abstract class OneAbstractMethod { /**/ } ',
                     );

?>