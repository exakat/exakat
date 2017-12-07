<?php

$expected     = array('a::nonStatic( )',
                     );

$expected_not = array('a::reallyStatic( )',
                      'a::doesntExist( )',
                      'a::nonStaticInBClass( )',
                      'a::reallyStaticInBClass( )',
                      'classDoesntExist::nonStatic( )',
                      'classDoesntExist::reallyStatic( )',
                      'classDoesntExist::doesntExist( )',
                      'classDoesntExist::nonStaticInBClass( )',
                      'classDoesntExist::reallyStaticInBClass( )',
                     );

?>