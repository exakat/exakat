<?php

$expected     = array('OriginalAliasBothUnusedo as OriginalAliasBothUnused',
                      'OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2',
);

$expected_not = array('OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda',
                      'OriginalAliasBothUsedo as OriginalAliasBothUseda',
                      'OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2',
                      'OriginalAliasBothUsedo2 as OriginalAliasBothUseda2',
                      
                      'OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda',
                      'OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2',
                      );
?>