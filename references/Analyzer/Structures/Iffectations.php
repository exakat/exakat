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

class Cornac_Auditeur_Analyzer_Structures_Iffectations extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Affectations in a if';
	protected	$description = 'Affectation in a if, or a while';

	
	public function analyse() {
        $this->cleanReport();

// @note in a if
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id 
JOIN <tokens> T2
    ON T1.file = T2.file AND TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file AND T3.left BETWEEN T2.left AND T2.right
JOIN <tokens>_cache TC
    ON TC.id = T3.id
WHERE T1.type='ifthen' AND
      TT.type = 'condition' AND
      T3.type = 'affectation'
SQL;
        $this->execQueryInsert('report', $query);

// @note in a while
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id 
JOIN <tokens> T2
    ON T1.file = T2.file AND TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file AND T3.left BETWEEN T2.left AND T2.right
JOIN <tokens>_cache TC
    ON TC.id = T3.id
WHERE T1.type='_while' AND
      TT.type = 'condition' AND
      T3.type = 'affectation'
SQL;
        $this->execQueryInsert('report', $query);

// @note in a do while
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id 
JOIN <tokens> T2
    ON T1.file = T2.file AND TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file AND T3.left BETWEEN T2.left AND T2.right
JOIN <tokens>_cache TC
    ON TC.id = T3.id
WHERE T1.type='_dowhile' AND
      TT.type = 'condition' AND
      T3.type = 'affectation'
SQL;
        $this->execQueryInsert('report', $query);
        
        return true;
	}
}

?>