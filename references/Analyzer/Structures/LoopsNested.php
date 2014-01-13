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

class Cornac_Auditeur_Analyzer_Structures_LoopsNested extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Nested loops';
	protected	$description = 'Loops (for, foreach, while) inside other loops. This is usually valid, but one must be cautious when using those, as they easily generate high loads.';

	
	public function analyse() {
        $this->cleanReport();

        $concat = $this->concat("T1.type","'->'","T2.type");
        $query = <<<SQL
SELECT NULL, T1.file, $concat, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file = T2.file AND 
       T2.left BETWEEN T1.left AND T1.right
WHERE T1.type IN ('_while','_for','_foreach') AND 
      T2.type IN ('_while','_for','_foreach')
GROUP BY T1.file, T1.left, T1.id, T2.type
SQL;
        $this->execQueryInsert('report', $query);
        
        return true;
	}
}

?>