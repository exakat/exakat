<?php
    function recursive($x) {
        recursive($x);
    }

    function nonrecursive($x) { }
    function nonrecursive2($x) { strlen($x); }
?>