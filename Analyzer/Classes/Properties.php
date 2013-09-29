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

class Cornac_Auditeur_Analyzer_Classes_Properties extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Properties';
	protected	$description = 'Defined properties';
	
	public function analyse() {
        $this->cleanReport();

// @todo remove this! T2 is bad for your health!
        $concat = $this->concat("T2.class","'->'","T2.code");

        $this->backend->setAnalyzerName($this->name);
        $this->backend->reportCode($concat)
                      ->type('_var')
                      ->notClass('global')
                      ->firstChild(1)
                      ->type('variable');
        $this->backend->run('attributes');

        return true;
    }
}

?>