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

class Cornac_Auditeur_Analyzer_Functioncalls extends Cornac_Auditeur_Analyzer {
    protected $not = false; 
    protected $functions = array();

    public function analyse() {
        if (!is_array($this->functions) || empty($this->functions)) {
            print "No function name provided for class ".get_class($this).". Aborting.\n";
            die(__METHOD__);
        }
        $in = join("','", $this->functions);

        if ($this->not) {
            $code = 'notcode';
        } else {
            $code = 'code';
        }
        
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->$code($this->functions);
        $this->backend->run();
    }
}

?>