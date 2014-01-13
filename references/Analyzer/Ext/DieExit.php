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

class Cornac_Auditeur_Analyzer_Ext_Dieexit extends Cornac_Auditeur_Analyzer_Functioncalls {
	protected	$title = 'Die and Exit';
	protected	$description = 'List of script endings with die and exit, or even return() when in global scope';

	
	public function analyse() {
        $this->functions = array('die','exit');
        parent::analyse();
        
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_return')
                      ->scope('global')
                      ->_class('');
        $this->backend->run();
        
        return true;
	}
}

?>