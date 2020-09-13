<?php

class x {
    function getValueKO($name) {}
    function getValueOK($name) {}
    function getValueFunction($name) {}
}

class y extends x {
    // consistent with the method above
    function getValueOK($name) {}
}

class z extends x {
    // inconsistent with the method above
    function getValueKO($label) {}
}

function getValueFunction($name) {}

?>