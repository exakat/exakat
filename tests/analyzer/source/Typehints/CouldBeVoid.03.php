<?php

abstract class x {
    abstract function barx($g);
    abstract function bary($g);
}

class y extends x {
    function barx($g) : void {}
    function bary($g) {}
}

?>