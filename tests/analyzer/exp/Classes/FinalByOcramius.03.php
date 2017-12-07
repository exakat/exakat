<?php

$expected     = array('class aOK implements i1 { /**/ } ',
                     );

$expected_not = array('abstract class abstractClass { /**/ } ',
                      'final class FinalClass { /** / }',
                      'class normalClass { /**/ } ',
                      'class normalClassNoImplements { /**/ } ',
                     );

?>