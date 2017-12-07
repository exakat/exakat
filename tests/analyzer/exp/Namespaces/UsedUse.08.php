<?php

$expected     = array('a\\b\\c\\d\\OriginalAlwaysUsed as AlwaysUsed',
                      'a\\b\\c\\d\\OriginalInTypeHint as InTypeHint',
                      'a\\b\\c\\d\\OriginalInExtends as InExtends',
                      'a\\b\\c\\d\\OriginalInImplementsAccompanied as InImplementsAccompanied',
                      'a\\b\\c\\d\\OriginalInNew as InNew',
                      'a\\b\\c\\d\\OriginalInStaticConstant as InStaticConstant',
                      'a\\b\\c\\d\\OriginalInStaticProperty as InStaticProperty',
                      'a\\b\\c\\d\\OriginalInImplementsAlone as InImplementsAlone',
                      'a\\b\\c\\d\\OriginalInStaticMethod as InStaticMethod',
                     );

$expected_not = array('a\\b\\c\\d\\OriginalNeverUsed as NeverUsed',
                     );

?>