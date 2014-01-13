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
class Cornac_Auditeur_Analyzer_Php_ClassesConflict extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Class name conflicts';
	protected	$description = 'Those classes may have conflicting name with PHP\'s classes, or some PHP extension\'s classes.';

	function dependsOn() {
	    return array('Classes_Definitions');
	}
	
	public function analyse() {
        $this->cleanReport();

        $constants = Cornac_Auditeur_Analyzer::getPHPClasses();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->table('report')
                      ->reportCode('element')
                      ->module('Classes_Definitions')
                      ->element($constants);
        $this->backend->run();

        return true;
	}
}

?>