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

class Cornac_Auditeur_Analyzer_Ext_Errors extends Cornac_Auditeur_Analyzer_Functioncalls {
	protected	$title = 'Error functions';
	protected	$description = 'List all error handling functions';

	
	public function analyse() {
	    $this->functions = array('debug_backtrace', 
	                             'debug_print_backtrace', 
	                             'error_get_last', 
	                             'error_log', 
	                             'error_reporting', 
	                             'restore_error_handler', 
	                             'restore_exception_handler', 
	                             'set_error_handler', 
	                             'set_exception_handler', 
	                             'trigger_error', 
	                             'user_error');
	    parent::analyse();
	    
	    return true;
	}
}

?>