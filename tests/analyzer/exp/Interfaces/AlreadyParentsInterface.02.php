<?php

$expected     = array('class b4 extends b3 implements i, j { /**/ } ',
                      'class a1 extends a0 implements i, j { /**/ } ',
                      'class a2 extends a1 implements i, j { /**/ } ',
                      'class a3 extends a2 implements i, j { /**/ } ',
                      'class a4 extends a3 implements i, j { /**/ } ',
                     );

$expected_not = array('class b1 extends b0 implements i, j { /**/ } ',
                      'class b2 extends b1 implements i, j { /**/ } ',
                      'class b3 extends b2 implements i, j { /**/ } ',
                      'class c1 extends c0 implements i, j { /**/ } ',
                      'class c2 extends c1 implements i, j { /**/ } ',
                      'class c3 extends c2 implements i, j { /**/ } ',
                      'class c4 extends c3 implements i, j { /**/ } ',
                      'class a0',
                      'class b0',
                      'class c0',
                     );

?>