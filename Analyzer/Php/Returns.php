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

class Cornac_Auditeur_Analyzer_Php_Returns extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Returns';
	protected	$description = 'Usage of return keyword';

	public function analyse() {
        $this->cleanReport();

        $concat = $this->concat("SUM(type='_return')", "' returns'");
        $this->backend->setAnalyzerName($this->name);
        $this->backend->reportCode($concat)
                      ->notScope(array( '__construct','__destruct','__set','__get','__call','__clone','__toString','__wakeup','__sleep'))
                      ->notClass('global')
                      ->notScope('global')
                      ->notScope()
                      ->groupBy(array('file', 'class', 'scope'));
        $this->backend->run();
        return true;
	}
}

?>