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


class Cornac_Auditeur_Analyzer_Php_Keywords extends Cornac_Auditeur_Analyzer {
	protected	$title = 'PHP keyword';
	protected	$description = 'Usage of PHP keywords in the application\'s literals, or structures\' names, not in the code source, where they belong. This may lead to confusion.';

	public function analyse() {
        $this->cleanReport();

        $in = Cornac_Auditeur_Analyzer::getPHPKeywords();

// @note used as literals
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('literals')
                      ->code($in);

        $this->backend->run();

// @note search in variables/properties
        $in_var = explode(',', '$'.join(',$', $in));
        $this->backend->type('variable')
                      ->code($in_var);

        $this->backend->run();

// @note used as function name
        $this->backend->type('_function')
                      ->code($in);
                      
        $this->backend->run();

        return true;
	}
}

?>