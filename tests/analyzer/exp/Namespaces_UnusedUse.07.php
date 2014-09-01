<?php

$expected     = array('f\\OriginalAliasBothUnusedo as OriginalAliasBothUnused', 
                      'b\\SingleWithoutAliasUnused', 
                      
                      'o\\OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2', 
                      'k\\SingleWithoutAliasUnused2');

$expected_not = array('a\\SingleWithoutAliasUsed', 
                      'c\\OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda', 
                      'd\\OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda',
                      'e\\OriginalAliasBothUsedo as OriginalAliasBothUseda',
                      
                      'j\\SingleWithoutAliasUsed2',
                      'l\\OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2',
                      'm\\OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2',
                      'n\\OriginalAliasBothUsedo2 as OriginalAliasBothUseda2'
                      );

?>