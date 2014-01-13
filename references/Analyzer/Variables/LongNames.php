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

class Cornac_Auditeur_Analyzer_Variables_LongNames extends Cornac_Auditeur_Analyzer { 
	protected	$title = 'Long variable names';
	protected	$description = 'Variables whose name is really long (> 20)';

	function dependsOn() {
	    return array('Variables_Names');
	}

	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, TR1.file, CONCAT(TR1.element, ' (', LENGTH(TR1.element),' chars)' ), TR1.id, '{$this->name}', 0
FROM <report> TR1
WHERE TR1.module = 'Variables_Names' AND 
      LENGTH(REPLACE(TR1.element, '$','')) > 19
GROUP BY BINARY TR1.id;
SQL;
        $this->execQueryInsert('report',$query);

        return true;
	}
}

?>