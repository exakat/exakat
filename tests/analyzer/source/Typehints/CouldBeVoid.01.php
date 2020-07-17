<?php

abstract class x {
    abstract function barx($g);
    function barx2($g) {}
    function barx3($g) { return; }
    function barx4($g) { if (rand(1,2)) {return;} else { return; } }
    function barx5($g) { if (rand(1,2)) {return;} else { return null; } }
}

interface i {
    function bari($g);
}
?>