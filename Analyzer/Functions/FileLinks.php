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

class Cornac_Auditeur_Analyzer_Functions_FileLinks extends Cornac_Auditeur_AnalyzerDot {
	protected	$title = 'Relations between functions';
	protected	$description = 'Identify links between files, when a function defined in one file is called in another.';

// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array('Structures_FunctionsCalls','Functions_Definitions');
	}
	
	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT TR1.file, TR2.file, TR1.element, '{$this->name}'
FROM <report> TR1
JOIN <report> TR2
    ON TR2.module = 'Structures_FunctionsCalls' AND
       TR2.element = TR1.element
WHERE TR1.module='Functions_Definitions'
SQL;
        $this->execQueryInsert('report_dot', $query);
        
        return true;
	}
}

?>