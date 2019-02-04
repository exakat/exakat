<?php

class x implements countable {
    function count() {
        return 1;
    }
}

class y {
    function count() {
        return 1;
    }
}


class z implements \Countable {
    function count() {
        return 1;
    }
}

class zz extends z {
    function count() {
        return 1;
    }
}

?>