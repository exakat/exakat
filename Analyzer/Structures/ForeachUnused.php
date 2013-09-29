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

class Cornac_Auditeur_Analyzer_Structures_ForeachUnused extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused variables in a foreach';
	protected	$description = 'Spot unused variables in a foreach loop. For example : foreach($a as $k => $v) {     }';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}
	
	public function analyse() {
        $this->cleanReport();

// @todo sync search for index and variables
// @todo do the search for properties, array, and any mix

// @doc spot unused variables in index
	    $query = <<<SQL
INSERT INTO <report> 
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'key'
JOIN <tokens> T2
    ON TT.token_sub_id = T2.id AND
       T2.file = T1.file
JOIN <tokens_tags> TT2
    ON TT2.token_id = T1.id AND
       TT2.type = 'block'
JOIN <tokens> T3
    ON TT2.token_sub_id = T3.id AND
       T3.file = T1.file
LEFT JOIN <tokens> T4
    ON T4.file = T1.file AND
       T4.left BETWEEN T3.left AND T3.right AND
       T4.code = T2.code
WHERE T1.type='_foreach' AND
      T4.id IS NULL;
SQL;
        $this->execQuery($query);

// @todo spot unused variables in index as reference
// @todo spot unused properties in index
// @todo spot unused array in index
// @todo spot unused properties in index as reference
// @todo spot unused array in index as reference
// @todo spot unused references


// @doc spot unused variables in value
	    $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'value'
JOIN <tokens> T2
    ON TT.token_sub_id = T2.id AND
       T2.file = T1.file AND
       T2.type = 'variable'
JOIN <tokens_tags> TT2
    ON TT2.token_id = T1.id AND
       TT2.type = 'block'
JOIN <tokens> T3
    ON TT2.token_sub_id = T3.id AND
       T3.file = T1.file
LEFT JOIN <tokens> T4
    ON T4.file = T1.file AND
       T4.left BETWEEN T3.left AND T3.right AND
       T4.code = T2.code
WHERE T1.type='_foreach' AND 
      T4.id IS NULL;
SQL;
        $this->execQueryInsert('report', $query);

// @doc spot unused variables in value as reference
	    $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'value'
JOIN <tokens> T2a
    ON TT.token_sub_id = T2a.id AND
       T2a.file = T1.file AND 
       T2a.type = 'reference'
JOIN <tokens> T2
    ON T2.file = T1.file AND 
       T2a.left + 1 = T2.left
JOIN <tokens_tags> TT2
    ON TT2.token_id = T1.id AND
       TT2.type = 'block'
JOIN <tokens> T3
    ON TT2.token_sub_id = T3.id AND
       T3.file = T1.file
LEFT JOIN <tokens> T4
    ON T4.file = T1.file AND
       T4.left BETWEEN T3.left AND T3.right AND
       T4.code = T2.code
WHERE T1.type='_foreach' AND 
      T4.id IS NULL;
SQL;
        $this->execQueryInsert('report', $query);

// @todo spot unused variables in value as reference
// @todo spot unused properties in value
// @todo spot unused array in value
// @todo spot unused properties in value as reference
// @todo spot unused array in value as reference
        
        return true;
	}
}

?>