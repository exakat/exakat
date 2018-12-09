<?php

array_map(array('static', 'moo'), range(1,3));
array_map(array('self', 'moo'), range(1,3));
array_map(array('PARENT', 'moo'), range(1,3));

class x {
    function boo() {
        array_map(array('Static', 'boo'), range(1,3));
        array_map(array('Self', 'boo'), range(1,3));
        array_map(array('Parent', 'boo'), range(1,3));
    }
}

class y extends x {
    function boo() {
        array_map(array('static', 'poo'), range(1,3));
        array_map(array('self', 'poo'), range(1,3));
        array_map(array('PARENT', 'Poo'), range(1,3));
    }
}

?>