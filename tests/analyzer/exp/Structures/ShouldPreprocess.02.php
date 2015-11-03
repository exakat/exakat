<?php

$expected     = array("explode('.', 1)",
                      "explode('.', \"q.w.e.r.t.ty.y.u.ui\")",
                      "explode('.', strtolower(\"q.w.e.r.t.ty.y.u.ui\"))",
                      "join('.', array(1, 2, 3, 4, 5))",
                      "explode('.', strtoupper(\"q.w.e.r.t.ty.y\"))"
);

$expected_not = array();

?>