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


class Cornac_Auditeur_Analyzer_Classes_MagicMethodWrongVisibility extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Magic methods with wrong visibility';
	protected	$description = 'Spot Magic methods with wrong visibility. This is now infored in PHP 5.3!';

    function dependsOn() {
        return array('Classes_Private','Classes_Protected', 'Classes_Static');
    }

	public function analyse() {
        $this->cleanReport();

        $magic_methods = array('__get','__set','__unset','__isset','__call');
        $this->backend->setAnalyzerName($this->name);
        $this->backend->reportCode('CONCAT(T1.class,"::",T1.code)')
                      ->type('_function')
                      ->code($magic_methods)
                      ->attributes('Classes_Private');
        $this->backend->run();

        $this->backend->reportCode('CONCAT(T1.class,"::",T1.code)')
                      ->type('_function')
                      ->code($magic_methods)
                      ->attributes('Classes_Protected');
        $this->backend->run();

// @todo make this a real concat() call, not hardwired
        $this->backend->reportCode('CONCAT(T1.class,"::",T1.code)')
                      ->type('_function')
                      ->code($magic_methods)
                      ->attributes('Classes_Static')
                      ->uniqueId($this->name, 'id');
        $this->backend->run();

        return true;
	}
}

?>