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

class Cornac_Auditeur_Analyzer_Php_GlobalsLinks extends Cornac_Auditeur_AnalyzerDot {
	protected	$title = 'File network of globals';
	protected	$description = 'List of file dependencies, based on globals';

	function dependsOn() {
	    return array('Php_Globals');
	}

	public function analyse() {
        $this->cleanReport();
        
        $query = <<<SQL
SELECT DISTINCT TR1.file, TR2.file, TR1.element, '{$this->name}'
FROM <report> TR1
JOIN <report> TR2
    ON TR1.element = TR2.element AND
       TR2.module='globals'
WHERE TR1.module='globals'
SQL;
        $res = $this->execQueryInsert('report_dot', $query);
	}
}

?>