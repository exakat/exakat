<?php

$expected     = array('\'dist.exakat-info.com\'', 
                      '\'exakat.com.au\'', 
                      '\'g.co\'', 
                      '\'www.google.com\'', 
                      '\'exakat.t.t.co\'', 
                      '\'google.com\'', 
                      '\'exakat.asia\'', 
                      '\'exakat.com\'', 
                      '\'exakat-info.com\'', 
                      '\'dist.exakat.com\'');

$expected_not = array('\'exakat.t.t.c\'',
                      '\'exakat,com\'',
                      '\'exakat\'',
                      '\'exakat.123\'',
                      '\'.com\'',
                      '\'exakat.com/users\'',
                      '\'-exakat.com\'',
                      '\'exakat-.com\'',
                      '\'dist.-exakat.com\'',
                      '\'dist.exakat-.com\'',
                      );
?>