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

class Cornac_Auditeur_Analyzer_Constants_Definitions extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Constants definitions';
	protected	$description = 'List of constants definitions.';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->code('define')
                      ->getTaggedToken('args')
                      ->firstChild()
                      ->reportCode('cache_code');
        $this->backend->run();

        $this->backend->type('constant_class')
                      ->_class('')
                      ->firstChild()
                      ->reportCode();
        $this->backend->run();

        return true;
	}
}

?>