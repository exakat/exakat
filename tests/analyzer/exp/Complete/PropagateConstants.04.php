<?php

$expected     = array('1',
                      '1',
                      '1',
                      '1',
                      '',
                      '',
                     );

$expected_not = array('1',
                      '',
                     );

$fetch_query = 'g.V().hasLabel("Constant").out("VALUE").values("boolean")';

?>