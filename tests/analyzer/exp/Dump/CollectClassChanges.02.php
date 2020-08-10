<?php

$expected     = array(array('changeType'  => 'Constant Visibility',
                            'name'        => 'Cb1',
                            'parentClass' => 'class b extends a { /**/ } ',
                            'parentValue' => 'private Cb1',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'public Cb1',
                            ),
                       array('changeType' => 'Constant Visibility',
                            'name'        => 'Ca1',
                            'parentClass' => 'class a { /**/ } ',
                            'parentValue' => 'private Ca1',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'protected Ca1',
                            ),
                     );

$expected_not = array( array('changeType'  => 'Constant Visibility',
                            'name'        => 'Ca2',
                            'parentClass' => 'class a { /**/ } ',
                            'parentValue' => 'private Ca2',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'private Ca2',
                            ),
                        array('changeType'  => 'Constant Visibility',
                            'name'        => 'Cb2',
                            'parentClass' => 'class b extends a { /**/ } ',
                            'parentValue' => 'private Cb2',
                            'childClass'  => 'class c extends b { /**/ } ',
                            'childValue'  => 'private Cb2',
                            ),
                     );

?>