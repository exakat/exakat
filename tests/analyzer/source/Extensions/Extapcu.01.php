<?php
apc_clear_cache();
apc_store('foo', 42);
$dump = apcu_bin_dump(array('foo'));
apc_clear_cache();
var_dump(apc_fetch('foo'));
apcu_bin_load($dump, APC_BIN_VERIFY_MD5 | APC_BIN_VERIFY_CRC32);
var_dump(apc_fetch('foo'));
?>