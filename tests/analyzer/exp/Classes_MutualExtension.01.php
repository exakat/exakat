<?php

// reported class may depends on storing order in the database.
$expected     = array('class a extends b', 
                      'class g2 extends \\e2', 
                      'class f extends g');

$expected_not = array('class d extends c');

?>