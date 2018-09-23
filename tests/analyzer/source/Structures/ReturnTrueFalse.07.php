<?php
class x {
    function foo() {
            if (WITH_ARRAYS) {
                $a =array('a' => 'b',
                          'c' => 'd', );
            } else {
                $a =array();
            }
        }

    function foo2() {
            if (WITH_BOOLEAN) {
                $a =true;
            } else {
                $a =false;
            }
        }
}
?>