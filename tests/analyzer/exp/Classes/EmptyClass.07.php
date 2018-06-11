<?php

$expected     = array('class alsoEmptyDerivedClass2 extends B { /**/ } ',
                      'class alsoEmptyClass { /**/ } ',
                      'class alsoEmptyClass2 { /**/ } ',
                      'class emptyClass { /**/ } ',
                     );

$expected_not = array('class nonEmptyClass { /**/ } ',
                      'class nonEmptyDerivedClass3 extends B { /**/ } ',
                      'class nonEmptyDerivedClass4 extends B { /**/ } ',
                     );

?>