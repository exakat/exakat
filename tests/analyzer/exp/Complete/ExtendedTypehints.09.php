<?php

$expected     = array('interface A { /**/ } ',
                      'class B implements A { /**/ } ',
                      'interface A2 extends A { /**/ } ', 
                      'class C2 extends B { /**/ } ', 
                      'class D2 implements A2 { /**/ } ',
                     );

$expected_not = array('interface C { /**/ } ',
                      'class D implements E { /**/ } ',
                     );

$fetch_query = 'g.V().hasLabel("Ppp").out("TYPEHINT").in("DEFINITION").values("fullcode")';

?>