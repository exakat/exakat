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

class Cornac_Auditeur_Analyzer_Names extends Cornac_Auditeur_Analyzer {

	
	public function analyse() {
	// @doc search for tokens by name and type
	    $type_token = $this->noms['type_token'];
	    $type_tag = $this->noms['type_tags'];
        $this->noms = array();

        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type($type_token)
                      ->getTaggedToken($type_tag)
                      ->reportCode();
        $this->backend->run();
        
        return true;
    }
}

?>