<?php

class x {}

class y {
    function __toString() {}
}

class z implements stringable {
    function __toString() {}
}

class zz extends z {
    function __toString() {}
}

class a implements stringable {
    function __not() {}
}

?>