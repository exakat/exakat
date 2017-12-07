<?php

$expected     = array('class ANotException extends Exception { /**/ } ',
                      'class BH extends ANotException { /**/ } ',
                      'class BG extends G { /**/ } ',
                     );

$expected_not = array('class BD extends D { /**/ } ',
                      'class AException extends \\Exception { /**/ } ',
                      'class B extends Aexception { /**/ } ',
                      'class BE extends E { /**/ } ',
                      'class BC extends C { /**/ } ',
                      'class BF extends F { /**/ } ',
                     );

?>