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

class Cornac_Auditeur_Analyzer_Classes_Hierarchy extends Cornac_Auditeur_AnalyzerDot {
    protected    $title = 'Classes hierarchy';
    protected    $description = 'Classes hierarchy, through extends keyword';
    
    public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_class')
                      ->getTaggedToken('extends')
                      ->reportOrigin('code')
                      ->reportDestination('class');
                      
        $this->backend->run('dot');
        
        return true;
    }
}

?>