<?php

$expected     = array('class b4 extends b3 implements i { /**/ } ',
                      'class a1 extends a0 implements i { /**/ } ',
                      'class a2 extends a1 implements i { /**/ } ',
                      'class a3 extends a2 implements i { /**/ } ',
                      'class a4 extends a3 implements i { /**/ } ',
                     );

$expected_not = array('class b1 extends b0 implements i { /**/ } ',
                      'class b2 extends b1 implements i { /**/ } ',
                      'class b3 extends b2 implements i { /**/ } ',
                      'class c1 extends c0 implements i { /**/ } ',
                      'class c2 extends c1 implements i { /**/ } ',
                      'class c3 extends c2 implements i { /**/ } ',
                      'class c4 extends c3 implements i { /**/ } ',
                      'class a0',
                      'class b0',
                      'class c0',
                     );

?>