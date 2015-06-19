<?php

$expected     = array('apcu_bin_load($dump, APC_BIN_VERIFY_MD5 | APC_BIN_VERIFY_CRC32)', 
                      'apcu_bin_dump(array(\'foo\'))');

$expected_not = array();

?>