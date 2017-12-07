<?php

$expected     = array('\'{__NORUNTIME__}\'',
                      '"%123$x4%u"',
                      '\'%123\' . $x . \'4%u\'',
                     );

$expected_not = array('\'__NOTREGEX__\'',
                     );

?>