<?php

$expected     = array('interface A { /**/ } ',
                      'class B implements A { /**/ } ',
                     );

$expected_not = array('interface C { /**/ } ',
                      'class D implements E { /**/ } ',
                     );

$fetch_query = 'g.V().hasLabel("Function").out("RETURNTYPE").in("DEFINITION").values("fullcode")';

?>