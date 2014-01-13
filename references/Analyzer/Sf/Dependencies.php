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

class Cornac_Auditeur_Analyzer_Sf_Dependencies extends Cornac_Auditeur_Analyzer_Classesusage {
	protected	$title = 'Sf : Symfony dependance';
	protected	$description = 'Dependencies toward Symfony Framework : by heritage or composition, those classes from the Symfony are needed.';


	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

// @note heritage
        $in = Cornac_Auditeur_Analyzer::getSymfonyClasses();
        $this->in = join('", "', $in);
        
        return parent::analyse();
	}
}
?>