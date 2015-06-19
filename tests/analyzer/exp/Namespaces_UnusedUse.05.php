<?php

$expected     = array('SingleWithoutAliasUnused', 
                      'OriginalAliasBothUnusedo as OriginalAliasBothUnused',
                      'SingleWithoutAliasUnused2', 
                      'OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2',
);

$expected_not = array('SingleWithoutAliasUsed',
                      'OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda',
                      'OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda',
                      'OriginalAliasBothUsedo as OriginalAliasBothUseda',
                      'SingleWithoutAliasUsed2',
                      'OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2',
                      'OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2',
                      'OriginalAliasBothUsedo2 as OriginalAliasBothUseda2',
                      );
?>