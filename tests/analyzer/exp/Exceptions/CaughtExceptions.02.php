<?php

$expected     = array('class c extends b',
                      'class a extends \\RuntimeException',
                      'class ad extends \\RuntimeException',
                      'class b extends a',
);

$expected_not = array('class ae extends \\Exception',);

?>