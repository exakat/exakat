<?php

$expected     = array('apcu_bin_load($dump, APC_BIN_VERIFY_MD5 | APC_BIN_VERIFY_CRC32)',
                      'apcu_bin_dump(array(\'foo\'))',
                      'APC_BIN_VERIFY_CRC32',
                      'APC_BIN_VERIFY_MD5',
                     );

$expected_not = array('var_dump(apc_fetch(\'foo\'))',
                     );

?>