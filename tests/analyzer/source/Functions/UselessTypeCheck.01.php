<?php
 
 function foo1(A $a) { if (is_null($a)) {}}
 function foo11($a) { if (is_null($a)) {}}
 function foo12($a = null) { if (is_null($a)) {}}

 function foo2(A $a) { if ($a === null) {}}
 function foo21(A $a) { if ($a !== null) {}}
 function foo22(A $a) { if ($a != null) {}}
 function foo23(A $a) { if ($a <> null) {}}
 function foo24(A $a) { if ($a > 3) {}}


 function foo3(?A $a = null) { }

 function foo31(A $a = null) { }

?>