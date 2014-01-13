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

class Cornac_Auditeur_Analyzer_Quality_GpcAssigned extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Affectation in GPC variables';
	protected	$description = 'GPC variables that receive new values.';

	
	public function analyse() {
        $this->cleanReport();

        $gpc_regexp = '(\\\\'.join('|\\\\',Cornac_Auditeur_Analyzer::getPHPGPC()).')';

// @doc variables, not whole arrays
        $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id,'{$this->name}', 0
FROM <tokens> T1  
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id AND 
       TT.type='left'
JOIN <tokens> T2
    ON T2.file = T1.file AND TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND 
       T3.type='_array' AND 
       T3.left between T2.left AND T2.right 
JOIN <tokens_cache> TC
  ON TC.id = T3.id
WHERE T1.type = 'affectation' AND
      TC.code REGEXP '^$gpc_regexp'
SQL;
        $this->execQueryInsert('report', $query);

// @doc full arrays,  not just variables
        $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id,'{$this->name}', 0
FROM <tokens> T1  
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id AND 
       TT.type='left'
JOIN <tokens> T2
    ON T2.file = T1.file AND 
       TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND 
       T3.type='variable' AND 
       T3.left between T2.left AND T2.right 
LEFT JOIN <tokens> T4
    ON T4.file = T1.file AND 
       T4.left=T3.left -1 
JOIN <tokens_cache> TC
    ON TC.id = T3.id
WHERE T1.type = 'affectation' AND
      (T4.type IS NULL OR T4.type != '_array') AND 
      TC.code REGEXP '^$gpc_regexp'
SQL;
        $this->execQueryInsert('report', $query);
        
        return true;
    }
}

?>