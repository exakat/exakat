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

class Cornac_Auditeur_Analyzer_Structures_NestedLoads extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Loaded loops';
	protected	$description = 'Loops applied on a heavy function/method';

	
	// @todo check this. One need the list of heavy functions...!
	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file = T2.file AND 
       T2.left BETWEEN T1.left AND T1.right
WHERE T1.type in ('_while','_for','_foreach') AND 
      T2.type IN  ('_functionname_')
GROUP BY T1.file, T1.left, T2.type
HAVING COUNT(*) > 1
SQL;

        $this->execQueryInsert('report', $query);
        
        return true;
	}
}

?>