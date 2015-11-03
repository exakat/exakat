<?php

$expected     = array( 'a::nonStatic( )',
                       'classDoesntExist::nonStatic( )',
                       'classDoesntExist::reallyStatic( )',
                       'classDoesntExist::doesntExist( )',
                       'classDoesntExist::nonStaticInBClass( )',
                       'classDoesntExist::reallyStaticInBClass( )',
);

$expected_not = array( 'a::reallyStatic( )',
                       'a::doesntExist( )',
                       'a::nonStaticInBClass( )',
                       'a::reallyStaticInBClass( )',
);

?>