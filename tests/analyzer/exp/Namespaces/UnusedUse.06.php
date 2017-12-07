<?php

$expected     = array('OriginalAliasBothUnusedo as OriginalAliasBothUnused',
                      'OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2',
                      'OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda',
                      'OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2',
                     );

$expected_not = array('OriginalAliasBothUsedo as OriginalAliasBothUseda',
                      'OriginalAliasBothUsedo2 as OriginalAliasBothUseda2',
                      'OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda',
                      'OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2',
                     );

?>