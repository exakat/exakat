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

class Cornac_Auditeur_Analyzer_Php_ObsoleteFunctionsIn53 extends Cornac_Auditeur_Analyzer_Functioncalls {
	protected	$title = 'Obsolete PHP functions in 5.3';
	protected	$description = 'List of PHP functions, obsolete in 5.3';

	public function analyse() {
	    $this->functions = array(
'call_user_method',
'call_user_method_array',
'define_syslog_variables',
'dl',
'ereg',
'ereg_replace',
'eregi',
'eregi_replace',
'set_magic_quotes_runtime',
'session_register',
'session_unregister',
'session_is_registered',
'set_socket_blocking',
'split',
'spliti',
'sql_regcase',
'mysql_db_query',
'mysql_escape_string',
);
	    parent::analyse();
	}
}

?>