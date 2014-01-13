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


class Cornac_Auditeur_Analyzer_Structures_LoopsInfinite extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Infinite loops';
	protected	$description = 'Spot explicit infinite loops : that may be while or for.';


	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

// @todo there are probably more situations that lead to some infinite loop : we look for the one that are explicit
// @note we support while with constant condition, for with constant end, for with constant increment

// @note while with no variables : probably infinite loop
	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ', T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file =T2.file AND
       T1.left + 1 = T2.left
LEFT JOIN <tokens> T3
    ON T1.file = T3.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.type = 'variable'
WHERE T1.type = '_while'
GROUP BY T1.id
HAVING COUNT(T3.id) = 0
SQL;
        $this->execQueryInsert('report', $query);
        
// @note for with no middle code
	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ', T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
LEFT JOIN <tokens_tags> TT
ON TT.token_id = T1.id AND
   TT.type='end'
WHERE T1.type = '_for' AND
      TT.token_id IS NULL
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ', T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
ON TT.token_id = T1.id AND
   TT.type='end'
JOIN <tokens> T2
    ON T2.file = T1.file AND
       TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.type = 'variable'
WHERE T1.type = '_for' 
GROUP BY T1.id
HAVING COUNT(*) = 0
SQL;
        $this->execQueryInsert('report', $query);

// @note for with no increment code, or non-variable code
	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ', T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
LEFT JOIN <tokens_tags> TT
ON TT.token_id = T1.id AND
   TT.type='increment'
LEFT JOIN <report> TR
    ON T1.id = TR.token_id
WHERE T1.type = '_for' AND
      TT.token_id IS NULL AND
      TR.token_id IS NULL
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT('line ', T1.line), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
ON TT.token_id = T1.id AND
   TT.type='increment'
JOIN <tokens> T2
    ON T2.file = T1.file AND
       TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.type = 'variable'
WHERE T1.type = '_for' 
GROUP BY T1.id
HAVING COUNT(*) = 0
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>