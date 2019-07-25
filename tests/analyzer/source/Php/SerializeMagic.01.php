<?php

class x {
    function __serialize() {}
    function __unserialize() {}
}

class y {
    function _serialize() {}
    function _unserialize() {}
}

trait t {
    function   __SERialize() {}
    function __unSERialize() {}
}

?>