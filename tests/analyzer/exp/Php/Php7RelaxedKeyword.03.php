<?php

$expected     = array( '$b->foreach', 
                       '$d->include', 
                       '$a->do', 
                       '$c->while', 
                       'C::$while', 
                       'A::$do', 
                       'B::$then', 
                       'D::$include', 
                       'B::then', 
                       'D::include', 
                       'C::while', 
                       'A::do');

$pxpected_not = array('normal',
                      'normalstatic',
                      '$normalStatic');

?>