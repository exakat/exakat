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


class Cornac_Auditeur_Analyzer_Structures_IfWithoutComparison extends Cornac_Auditeur_Analyzer {
	protected	$title = 'If without comparison';
	protected	$description = 'Spot if conditions without explicit comparison, like if ($x) or if (count($t))';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

// @doc check for everything except logical and (not or noscream)
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
JOIN <tokens> T2 
ON T2.file = T1.file AND
   T2.left = T1.left + 2 AND
   T2.type NOT IN ('logical','not','noscream')
JOIN <tokens_cache> TC
    ON TC.id = T2.id
WHERE T1.type IN ('ifthen', '_while');

SQL;
        $this->execQueryInsert('report', $query);

// @doc check for everything in a not or noscream except logical
// @not one can mix not and noscream.... 
	    $query = <<<SQL
SELECT NULL, T1.file, T3.code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
JOIN <tokens> T2 
ON T2.file = T1.file AND
   T2.left = T1.left + 2 AND
   T2.type IN ('not','noscream')
JOIN <tokens> T3
ON T3.file = T1.file AND
   T3.left = T1.left + 3 AND
   T3.type NOT IN ('logical')
WHERE T1.type IN ('ifthen', '_while')
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>