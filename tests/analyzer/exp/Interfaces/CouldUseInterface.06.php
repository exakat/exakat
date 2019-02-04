<?php

$expected     = array('class a1 { /**/ } ',
                      'class a4 { /**/ } ',
                      'class a5 { /**/ } ',
                     );

$expected_not = array('class a0 implements sessionidinterface { /**/ } ',
                      'class a3 implements sessionidinterface, i { /**/ } ',
                      'class a2 implements i { /**/ } ',
                     );

?>