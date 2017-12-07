<?php

$expected     = array('SingleWithoutAliasUsed',
                      'OriginalUsed2',
                      'OriginalBothUsedSingleAlias as SingleAliasBothUsed',
                      'OriginalUnusedSingleAlias as SingleAliasUsed',
                      'OriginalBothUsed as AliasBothUsed',
                      'OriginalUnused as AliasUsed',
                     );

$expected_not = array('OriginalUsed as AliasUnused',
                      'OriginalUsedSingleAlias as SingleAliasUnused',
                      'OriginalUsed',
                     );

?>