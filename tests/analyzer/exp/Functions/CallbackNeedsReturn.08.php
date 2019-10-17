<?php

$expected     = array('array_map(function ($a5) { /**/ } , array_flip($tmp))',
                      'array_map(function ($a2) { /**/ } , array_flip($tmp))',
                     );

$expected_not = array('array_map(function ($a1) { /**/ } , array_flip($tmp))',
                      'array_map(function ($a3) { /**/ } , array_flip($tmp))',
                      'array_map(function ($a4) { /**/ } , array_flip($tmp))',
                     );

?>