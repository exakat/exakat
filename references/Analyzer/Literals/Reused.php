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

class Cornac_Auditeur_Analyzer_Literals_Reused extends Cornac_Auditeur_Analyzer { 
	protected	$title = 'Reused literals';
	protected	$description = 'Literals that are reused throughout the code. May be worth putting in constant, or centralized anyway.';


	function dependsOn() {
	    return array('Literals_Definitions');
	}

	public function analyse() {
        $this->cleanReport();

// @note temporary table, so has to avoid concurrency conflict
        $query = <<<SQL
CREATE TEMPORARY TABLE {$this->name}_TMP 
SELECT TRIM(code) AS code
FROM <tokens> TR1
WHERE type = 'literals' AND 
      TRIM(code) != ''
GROUP BY BINARY TRIM(code) 
HAVING COUNT(*) > 1
SQL;
        $this->execQuery($query);

        $query = <<<SQL
SELECT NULL, TR1.file, TRIM(TR1.code), TR1.id, '{$this->name}', 0
FROM <tokens> TR1
JOIN {$this->name}_TMP TMP
    ON TR1.type = 'literals' AND 
       TMP.code = TRIM(TR1.code)
SQL;
        $this->execQueryInsert('report', $query);

        $query = <<<SQL
DROP TABLE {$this->name}_TMP
SQL;
        $this->execQuery($query);

        return true;
	}
}

?>