<?php

$expected     = array( 'squareArray(( getArray( ) ))', 
                       'squareArray(( getArray( ) ), ( f( ) ))', 
                       'squareArray(( getArray( ) ), ( f( ) ), ( f2( ) ))'
);

$expected_not = array('(1) + (strtolower($x))',
                      '(strtoupper($x))');

?>