<?php

$expected     = array('1',
                      '3',
                      '6',
                      '10',
                     );

$expected_not = array('2',
                      '4',
                     );

$fetch_query = 'g.V().hasLabel("Constant").out("VALUE").values("intval")';

?>