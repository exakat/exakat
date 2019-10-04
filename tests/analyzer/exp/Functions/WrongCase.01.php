<?php

$expected     = array('x::StaticMETHOD( )',
                      '$b->METHOD( )',
                      'foO( )',
                      'FOO( )',
                      '\FOo( )',
                      '$b::StaticMETHOD( )',
                     );

$expected_not = array('foo( )',
                      '\\foo( )',
                      'x::Staticmethod( )',
                      '$b::Staticmethod( )',
                      '$b->method( )',
                     );

?>