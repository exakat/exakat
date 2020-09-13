<?php
    function d(string $f, a|array $a2, $g): string {
        // $r is ignored, as a local variable
        preg_match('/a/', $f, $r);

        $r1 = array();
        // $f is not at the right place
        preg_match('/b/', '', $f);

        // $a2 may be OK (array), so it is not reported
        preg_match('/c/', '', $a2);

        // $a2 may be OK (array), so it is not reported
        preg_match('/g/', '', $g);
    }

?>