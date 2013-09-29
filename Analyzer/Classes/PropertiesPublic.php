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

class Cornac_Auditeur_Analyzer_Classes_PropertiesPublic extends Cornac_Auditeur_Analyzer {
	protected	$description = 'Public properties';
	protected	$title = 'List of public properties in classes. Defined as such, or used as such.';

	
	public function analyse() {
        $this->cleanReport();

        // @doc case of simple public var and ppp : public $x
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_var')
                      ->reportCode("CONCAT(T1.class,'::',T1.code)")
                      ->attributes('Classes_Public');

        $this->backend->run();
        
        return true;
    // @todo support class and methods
    // @todo support also static and var keyword
    }
}

?>