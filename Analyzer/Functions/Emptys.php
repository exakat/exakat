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

class Cornac_Auditeur_Analyzer_Functions_Emptys extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Empty functions';
	protected	$description = 'Functions which body is empty (not abstract or interface functions).';


	function dependsOn() {
	    return array('Classes_Interfaces');
	}
	
	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, T4.code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
JOIN <tokens_tags> T2
    ON T1.id = T2.token_id
JOIN <tokens> T3
    ON T3.id = T2.token_sub_id
JOIN <tokens_tags> T5
    ON T1.id = T5.token_id     AND 
       T5.type = 'name'
JOIN <tokens> T4
    ON T1.file = T4.file       AND
       T4.id = T5.token_sub_id
LEFT JOIN <report> TR
    ON T1.file = TR.file       AND
       T4.class = TR.element   AND
       TR.module='Classes_Interfaces'
WHERE 
    T1.type = '_function'      AND
    T2.type = 'block'          AND
    T3.right - T3.left = 1     AND
    TR.id IS NULL
SQL;

        $this->execQueryInsert('report', $query);
	}
}

?>