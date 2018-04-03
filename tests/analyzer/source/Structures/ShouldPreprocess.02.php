<?php
    $a = join('.', array(1,2,3,4,5));
    $b = explode('.', "q.w.e.r.t.ty.y.u.ui");
    $c = explode('.', "q.w.e.r.t.ty.y.u.ui$d");
    $d = explode('.', strtolower("q.w.e.r.t.ty.y.u.ui"));
    $e = explode('.', strtoupper("q.w.e.r.t.ty.y"));
    $f = explode('.', 1);
    $f = $a->explode('.', 1);


    $A = join($b, array(1,2,3,4,$c));
    $c = explode('.', "q.w.e.r.t.ty.y.u.ui".$d);
?>