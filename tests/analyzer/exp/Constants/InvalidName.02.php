<?php

$expected     = array('\'我A\'',
                      '\'A我\'',
                      '\'我\'',
                      '\'f$oo\'',
                      '\'$foo\'',
                      '\'3foo\'',
                      '\'+3\'',
                     );

$expected_not = array('"FOO"',
                      '"FOO2"',
                      '"FOO_BAR"',
                      '\'fo3o\'',
                      '"frânçaïs"',
                     );

?>