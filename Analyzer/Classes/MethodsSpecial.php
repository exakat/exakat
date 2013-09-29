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

class Cornac_Auditeur_Analyzer_Classes_MethodsSpecial extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Special method';
	protected	$description = 'List of all PHP special methods';

	
	public function analyse() {
        $this->cleanReport();

        $methods = Cornac_Auditeur_Analyzer::getPhpSpecialMethods();
        $concat = $this->concat("T1.class","'->'","T1.scope");
        
        $this->backend->setAnalyzerName($this->name);
        $this->backend->reportCode($concat)
                      ->scope($methods)
                      ->notClass('')
                      ->groupBy(array('file', 'class', 'scope'));
        $this->backend->run();

        $this->backend->reportCode($concat)
                      ->scope()
                      ->notClass('')
                      ->groupBy(array('file', 'class', 'scope'));
        $this->backend->run();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->code('__autoload')
                      ->type('_function');
        $this->backend->run();
        
        return true;
	}
}

?>