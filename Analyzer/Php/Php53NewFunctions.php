<?php
/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */


class Cornac_Auditeur_Analyzer_Php_Php53NewFunctions extends Cornac_Auditeur_Analyzer_Functioncalls {
	protected	$title = 'New functions in PHP 5.3';
	protected	$description = 'Those are new functions in PHP 5.3. You may end up with conflict if you declared any of them.';

	public function analyse() {
	    $this->functions = array(
'array_replace',
'array_replace_recursive',
'class_alias',
'forward_static_call',
'forward_static_call_array',
'gc_collect_cycles',
'gc_disable',
'gc_enable',
'gc_enabled',
'get_called_class',
'gethostname',
'header_remove',
'lcfirst',
'parse_ini_string',
'quoted_printable_encode',
'str_getcsv',
'stream_context_set_default',
'stream_supports_lock',
'stream_context_get_params',
'date_add',
'date_create_from_format',
'date_diff',
'date_get_last_errors',
'date_parse_from_format',
'date_sub',
'timezone_version_get',
'gmp_testbit',
'hash_copy',
'imap_gc',
'imap_utf8_to_mutf7',
'imap_mutf7_to_utf8',
'json_last_error',
'mysqli_fetch_all',
'mysqli_get_connection_stats',
'mysqli_poll',
'mysqli_reap_async_query',
'openssl_random_pseudo_bytes',
'pcntl_signal_dispatch',
'pcntl_sigprocmask',
'pcntl_sigtimedwait',
'pcntl_sigwaitinfo',
'preg_filter',
'msg_queue_exists',
'shm_has_var',
'acosh',
'asinh',
'atanh',
'expm1',
'log1p',
);
	    parent::analyse();
	    
	    return true;
	}
}

?>