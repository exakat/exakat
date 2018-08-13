<?php

$expected     = array('SingleWithoutAliasUnused',
                      'OriginalBothUnusedSingleAlias as SingleAliasBothUnused',
                      'OriginalBothUnused as AliasBothUnused',
                      'OriginalUnused',
                      'OriginalUsedSingleAlias as SingleAliasUnused',
                      'OriginalUsed as AliasUnused',
                     );

$expected_not = array('OriginalUnusedSingleAlias as SingleAliasUsed',
                      'OriginalBothUsedSingleAlias as SingleAliasBothUsed',
                     );

?>