<?php

$expected     = array('interface A { /**/ } ',
                      'class B implements A { /**/ } ',
                      'class D2 implements A2 { /**/ } ', 
                      'interface A2 extends A { /**/ } ', 
                      'class C2 extends B { /**/ } ',
                     );

$expected_not = array('interface C { /**/ } ',
                      'class D implements E { /**/ } ',
                     );

$fetch_query = 'g.V().hasLabel("Function").out("ARGUMENT").out("TYPEHINT").in("DEFINITION").values("fullcode")';

?>