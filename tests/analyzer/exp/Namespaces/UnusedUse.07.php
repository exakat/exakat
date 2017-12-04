<?php

$expected     = array('f\\OriginalAliasBothUnusedo as OriginalAliasBothUnused',
                      'b\\SingleWithoutAliasUnused',
                      'c\\OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda',
                      'l\\OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2',
                      'o\\OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2',
                      'k\\SingleWithoutAliasUnused2',
                     );

$expected_not = array('a\\SingleWithoutAliasUsed',
                      'c\\OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda',
                      'd\\OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda',
                      'e\\OriginalAliasBothUsedo as OriginalAliasBothUseda',
                      'j\\SingleWithoutAliasUsed2',
                      'm\\OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2',
                      'n\\OriginalAliasBothUsedo2 as OriginalAliasBothUseda2',
                     );

?>