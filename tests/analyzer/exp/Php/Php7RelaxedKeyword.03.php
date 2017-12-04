<?php

$expected     = array('B::then',
                      'D::include',
                      'C::while',
                      'A::do',
                     );

$expected_not = array('C::$while',
                      'A::$do',
                      'B::$then',
                      'D::$include',
                      '$b->foreach',
                      '$d->include',
                      '$a->do',
                      '$c->while',
                      'normal',
                      'normalstatic',
                      '$normalStatic',
                     );

?>