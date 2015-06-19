<?php

function &DefinedWithAReference () {
    return $x;
}

class x {
    function &MethodDefinedWithAReference () {
        return $x;
    }
}
?>