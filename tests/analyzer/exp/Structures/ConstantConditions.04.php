<?php

$expected     = array('a ? \'b\' : \'c\'',
                      '0 ? \'bb\' : \'cc\'',
                      'true ?: \'bbb\'',
                     );

$expected_not = array('$x ?: \'bbbb\'',
                      '$y ?: \'bbbbb\'',
                     );

?>