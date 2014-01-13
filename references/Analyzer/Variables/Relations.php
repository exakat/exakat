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

class Cornac_Auditeur_Analyzer_Variables_Relations extends Cornac_Auditeur_AnalyzerDot {
	protected	$title = 'Link between variables';
	protected	$description = 'Linked variables : when two variables are in the same instructures ($x = $a + $b), then, they are in relation.';

	public function analyse() {
        $this->cleanReport();

// @todo : this should be done context by context. How can I do that? 
// @note I need another table for this        
        $query = <<<SQL
SELECT  T4.code, T2.code, CONCAT(T1.class,'::',T1.scope), '{$this->name}' 
FROM <tokens> T1
JOIN <tokens_tags> TT1
    ON T1.id = TT1.token_id AND 
       TT1.type='left'
JOIN <tokens> T2
    ON T2.id = TT1.token_sub_id AND 
       T2.type='variable' AND 
       T1.file =T2.file
JOIN <tokens_tags> TT2
    ON T1.id = TT2.token_id AND 
       TT2.type='right'
JOIN <tokens> T3
    ON T3.file = T1.file AND 
       T3.id = TT2.token_sub_id
JOIN <tokens> T4
    ON T4.file = T1.file AND 
       T4.left BETWEEN T3.left AND T3.right AND
       T4.type='variable'
WHERE T1.type = 'affectation'
SQL;
        $this->execQueryInsert('report_dot', $query);

        return true;
	}
}

?>