<?php

$expected     = array('isset($a) ? $a : \'b\'',
                      '!isset($a) ? \'b\' : $a',
                      '$a->f === NULL ? $a->f : \'b\'',
                      'nuLL === $a->f ? $a->f : \'b\'',
                      'nuLL !== $a->f ? \'b\' : $a->f',
                      'if(($model = Model::get($id)) === NULL) { /**/ } ',
                      'if(NULL === ($model = Model::get($id))) { /**/ } ',
                     );

$expected_not = array('isset($a) ? $b : \'b\'',
                      'isset($a) ? \'b\' : $a',
                      '$a->f == NULL ? $a->f : \'b\'',
                     );

?>