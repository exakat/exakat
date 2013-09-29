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

class Cornac_Auditeur_Analyzer_Structures_Constants extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Constant structures';
	protected	$description = 'Spot structures that are basically constant : they are based on constant, literals. ';

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT T1.id
FROM <tokens> T1
WHERE type IN ('_constant','literals','class_constant','rawtext',
               '_functionname_','_logical_','_comparison_',
               '_classname_','constant_static','_operation_',
               '_affectation_')
SQL;
        $this->execQueryAttributes($this->name, $query);

// @todo also support functioncall, array(), etc. 
// @todo : optimize this loop of 3. How? 
// @note do this until results don't change (with a count on attributes)
for ($i =0 ; $i < 5; $i++) {
	    $query = <<<SQL
SELECT T1.id 
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.left BETWEEN T1.left AND T1.right AND
       T1.file = T2.file AND
       T1.level + 1 = T2.level
LEFT JOIN <report_attributes> TA
    ON TA.id = T2.id 
WHERE T1.type IN (
'operation',
'comparison',
'logical',
'parenthesis',
'arglist',
'functioncall',
'keyvalue',
'concatenation',
'_new',
'sequence',
'block',
'_nsname',
'inclusion',
'noscream',
'ternaryop')
GROUP BY T1.id
HAVING SUM(IF(TA.Structures_Constants = 'Yes', 1, 0)) = COUNT(*)
SQL;
        $this->execQueryAttributes($this->name, $query);
}

        return true;
	}
}

?>