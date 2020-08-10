<?php

$expected     = array(array('changeType'  => 'Constant Value',
                            'name'        => 'Cb1',
                            'parentClass' => 'class b extends a { /**/ } ',
                            'parentValue' => 'Cb1 = 3',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'Cb1 = 0',
                            ),
                       array('changeType' => 'Constant Value',
                            'name'        => 'Ca1',
                            'parentClass' => 'class a { /**/ } ',
                            'parentValue' => 'Ca1 = 1',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'Ca1 = 0',
                            ),
                     );

$expected_not = array( array('changeType'  => 'Constant Value',
                            'name'        => 'Ca2',
                            'parentClass' => 'class a { /**/ } ',
                            'parentValue' => 'Ca2 = 2',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'Ca2 = 2',
                            ),
                        array('changeType'  => 'Constant Value',
                            'name'        => 'Cb2',
                            'parentClass' => 'class b extends a { /**/ } ',
                            'parentValue' => 'Cb2 = 2',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'Cb2 = 2',
                            ),
                     );

?>