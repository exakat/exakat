<?php

$expected     = array('1',
                      '12',
                      '123',
                      '1234',
                      '1239',
                     );

$expected_not = array('0',
                      '10',
                     );

$fetch_query = 'g.V().hasLabel("Constant").out("VALUE").values("noDelimiter")';

?>