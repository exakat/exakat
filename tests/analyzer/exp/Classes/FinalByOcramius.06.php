<?php

$expected     = array('class aOK implements i1 { /**/ } ',
                     );

$expected_not = array('class aKO implements i2 { /**/ } ',
                      'abstract class abstractClass { /**/ } ',
                      'final class FinalClass { /** / }',
                      'class normalClass { /**/ } ',
                      'class normalClassNoImplements { /**/ } ',
                     );

?>