<?php
new x($_GET);
(new x)->foo($_POST);
(new x)->bar([]);

class x {
    function __construct($input) {
        foreach($input as $k0 => $v) {
            $this->$k0 = $v;
        }
    }

    function foo($input) {
        foreach($input as $k1 => $v) {
            $this->$k1 = $v;
        }
    }

    function bar($input) {
        foreach($input as $k2 => $v) {
            $this->$k2 = $v;
        }
    }

}
?>