<?php 

class b {
public function p() { return 3; }
}

(new b)->p() == 3;
(new b())->p() == 3;
(new \b())->p() == 3;
(new \b)->p() == 3;

?>