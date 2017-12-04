<?php

$expected     = array('function returnBool( ) : bool { /**/ } ',
                      'function returnInt( ) : int { /**/ } ',
                      'function returnFloat( ) : float { /**/ } ',
                      'function returnString( ) : string { /**/ } ',
                     );

$expected_not = array('function returnCallable( ) : callable { /**/ } ',
                     );

?>