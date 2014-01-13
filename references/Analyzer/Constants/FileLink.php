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

class Cornac_Auditeur_Analyzer_Constants_FileLink extends Cornac_Auditeur_AnalyzerDot {
    protected    $title = 'File relations via constants';
    protected    $description = 'Files that are using the same constants. They are now linked together';

// @doc if this analyzer is based on previous result, use this to make sure the results are here
    function dependsOn() {
        return array('Constants_Definitions','Constants_Usage');
    }
    
    public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->module('Constants_Definitions')
                      ->reportCluster('file')
                      ->intersect('Constants_Usage')
                      ->reportDestination();
                      
        $this->backend->run('dot');

        return true;
    }
}

?>