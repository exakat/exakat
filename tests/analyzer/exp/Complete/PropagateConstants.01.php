<?php

$expected     = array('8',
                      '6',
                      '5',
                     );

$expected_not = array('1',
                      '2',
                     );

$fetch_query = 'g.V().hasLabel("Constant").out("VALUE").values("intval")';
?>