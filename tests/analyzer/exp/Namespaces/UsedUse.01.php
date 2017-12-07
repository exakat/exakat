<?php

$expected     = array('OriginalAlwaysUsed as AlwaysUsed',
                      'OriginalInTypeHint as InTypeHint',
                      'OriginalInNew as InNew',
                      'OriginalInExtends as InExtends',
                      'OriginalInImplementsAccompanied as InImplementsAccompanied',
                      'OriginalInImplementsAlone as InImplementsAlone',
                      'OriginalInStaticConstant as InStaticConstant',
                      'OriginalInStaticProperty as InStaticProperty',
                      'OriginalInStaticMethod as InStaticMethod',
                     );

$expected_not = array('OriginalNeverUsed as NeverUsed',
                     );

?>