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


class Cornac_Auditeur_Analyzer_Classes_ToStringNoArg extends Cornac_Auditeur_Analyzer {
    protected    $title = 'ToString with arguments';
    protected    $description = 'Spot __toString methods with arguments (Incompatible change for PHP 5.3)';
    protected    $tags = array('PHP_5.3');

    public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        // @todo this reportCode should be automated by backend. 
        $this->backend->type('_function')
                      ->code('__toString')
                      ->notClass('')
                      ->getTaggedToken('args');
        $this->backend->run();

        return true;
    }
}

?>