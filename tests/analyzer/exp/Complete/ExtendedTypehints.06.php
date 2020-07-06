<?php

$expected     = array('class B implements A { /**/ } ',
                      'class C2 extends B { /**/ } ',
                     );

$expected_not = array('interface A { /**/ } ',
                      'interface C { /**/ } ',
                      'class D implements E { /**/ } ',
                     );

$fetch_query = 'g.V().hasLabel("Function").out("ARGUMENT").out("TYPEHINT").in("DEFINITION").values("fullcode")';

?>