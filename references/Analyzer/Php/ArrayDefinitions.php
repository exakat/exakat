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

class Cornac_Auditeur_Analyzer_Php_ArrayDefinitions extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Arrays as lists';
	protected	$description = 'Long arrays, that contains data dictionaries, or lists';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_functionname_')
                      ->code('array')
// @todo it is not obvious when we change token. We need some prefix/suffixe to make this obvious

                      ->firstChild(2)
                      ->type('arglist')
                      ->groupby('id')
                      ->reportFile()
                      ->reportId()

                      ->inToken()
                      ->hasLevel(1)
// @todo remove this : this is SQL code. Must be moved into a function that counts...
                      ->reportCode("CONCAT(SUM(IF(T3.type='_empty_',0,1)), ' elements')");
        $this->backend->run();

        return true;
	}
}

?>