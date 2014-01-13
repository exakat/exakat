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


class Cornac_Auditeur_Analyzer_Structures_LinesLoaded extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Loaded lines';
	protected	$description = 'Lines with too many opcode (except for literals) : this is probably too much processing on one line.';


	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ',T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.type != 'literals'
GROUP BY file, line 
HAVING COUNT(*) > 10
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>