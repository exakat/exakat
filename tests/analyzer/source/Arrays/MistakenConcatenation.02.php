<?php

$normal = array(1,2,3,4);
$abnormal = array(1,2,foo()."3",5);

$normal = array('a', 'b', 'c'. $d);
$abnormal = array('a', 'b', 'c'. 'd');
$abnormal2 = array('a',( (int) $a)."b",'c', 'd');
$abnormalMultiple = array('a'. 'b', 'c'. 'd', 'e');
$normalMultiple = array('a'. 'b', 'c'. 'd'); // No string, so it's OK
$ok = array('a', 'b', 'c'. $d);

?>