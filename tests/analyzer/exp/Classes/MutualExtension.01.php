<?php

$expected     = array('class b extends a { /**/ } ',
                      'class a extends b { /**/ } ',
                      'class g extends e { /**/ } ',
                      'class f2 extends \\g2 { /**/ } ',
                      'class f extends g { /**/ } ',
                      'class e2 extends \\f2 { /**/ } ',
                      'class e extends f { /**/ } ',
                      'class g2 extends \\e2 { /**/ } ',
                     );

$expected_not = array('class c { /**/ } ',
                      'class d extends c { /**/ } ',
                     );

?>