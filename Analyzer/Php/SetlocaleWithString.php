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

class Cornac_Auditeur_Analyzer_Php_SetlocaleWithString extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Setlocale() with LC_ string';
	protected	$description = 'Spot usage of setlocale with string, instead of constants. This is an incompatibility for PHP 5.3.';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->code('setlocale')
                      ->firstChild(3)
                      ->firstChild()
                      ->reportCode()
                      ->type('literals')
                      ->code('LC_%');
        $this->backend->run();

        return true;
	}
}

?>