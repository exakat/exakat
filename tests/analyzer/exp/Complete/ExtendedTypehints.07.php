<?php

$expected     = array('class x { /**/ } ',
                      'class B extends X implements A { /**/ } ',
                     );

$expected_not = array('interface A { /**/ } ',
                      'interface C { /**/ } ',
                      'class D implements E { /**/ } ',
                     );

$fetch_query = 'g.V().hasLabel("Method").out("RETURNTYPE").in("DEFINITION").values("fullcode")';

?>