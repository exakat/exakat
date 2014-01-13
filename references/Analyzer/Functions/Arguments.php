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


class Cornac_Auditeur_Analyzer_Functions_Arguments extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Spot Function arguments in definitions';
	protected	$description = 'Spot Function arguments in definitions.';


	public function analyse() {
        $this->cleanReport();


        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->getTaggedToken('args')
                      ->inToken()
                      ->type('variable')
                      ->hasLevel(1)
                      ->reportId();
        $this->backend->run('attributes');

        $this->backend->type('_function')
                      ->firstChild(3)
                      ->type('arglist')
                      ->inToken()
                      ->type('variable')
                      ->reportId();
        $this->backend->run('attributes');

        return true;
	}
}

?>