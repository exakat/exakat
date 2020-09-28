<?php

$expected     = array('class ( ) extends \\AvoidThisClass { /**/ } ',
                      'fn ($a) : avoidThisClass => 1',
                      'catch (AvoidThisClass $a) { /**/ } ',
                     );

$expected_not = array('\'AvoidThisClass\'',
                      '\'Really AvoidThisClass\'',
                     );

?>