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

class Cornac_Auditeur_Analyzer_Php_Globals extends Cornac_Auditeur_Analyzer {
    protected    $title = 'Globals';
    protected    $description = 'Usage of global variables within the application';

    public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        // @note variable global thanks to the global reserved word
        $this->backend->type('_global')
                      ->firstChild();
        $this->backend->run();

        // @note variables globales because in $GLOBALS
        $this->backend->reset();
        $this->backend->type('_array')
                      ->reportCode('cache_code')
                      ->firstChild()
                      ->code('\$GLOBALS');
        $this->backend->run();

        return true;
    }    
    
}

?>