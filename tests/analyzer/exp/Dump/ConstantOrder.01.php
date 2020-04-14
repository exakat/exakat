<?php

$expected     = array( array
        (
            'built' => '\B',
            'built_fullcode' => 'B',
            'building' => '\A',
            'building_fullcode' => 'A',
        ),
        array(
            'built' => '\C',
            'built_fullcode' => 'C',
            'building' => '\i::I1',
            'building_fullcode' => 'i::I1',
),
array(
            'built' => '\C',
            'built_fullcode' => 'C',
            'building' => '\c::C2',
            'building_fullcode' => 'c::C2',
),
array(
           'built' => '\C',
            'built_fullcode' => 'C',
            'building' => '\A',
            'building_fullcode' => 'F',
),
array(
            'built' => '\C',
            'built_fullcode' => 'C',
            'building' => '\D',
            'building_fullcode' => '\D',
),
array(
            'built' => '\C2',
            'built_fullcode' => 'C2',
            'building' => '\c::C1',
            'building_fullcode' => 'self::C1',
),
                     );

$expected_not = array(array
        (
            'built' => '\B',
            'built_fullcode' => 'B',
            'building' => '\B',
            'building_fullcode' => 'B',
        )
                     );

/*
[0] => Array
        (
            [id] => 1
            [built] => \B
            [built_fullcode] => B
            [building] => \A
            [building_fullcode] => A
        )

    [1] => Array
        (
            [id] => 2
            [built] => \C
            [built_fullcode] => C
            [building] => \i::I1
            [building_fullcode] => i::I1
        )

    [2] => Array
        (
            [id] => 3
            [built] => \C
            [built_fullcode] => C
            [building] => \c::C2
            [building_fullcode] => c::C2
        )

    [3] => Array
        (
            [id] => 4
            [built] => \C
            [built_fullcode] => C
            [building] => \A
            [building_fullcode] => F
        )

    [4] => Array
        (
            [id] => 7
            [built] => \C
            [built_fullcode] => C
            [building] => \D
            [building_fullcode] => \D
        )

    [5] => Array
        (
            [id] => 8
            [built] => \C2
            [built_fullcode] => C2
            [building] => \c::C1
            [building_fullcode] => self::C1
        )


*/
?>