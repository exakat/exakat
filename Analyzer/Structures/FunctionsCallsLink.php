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

class Cornac_Auditeur_Analyzer_Structures_FunctionsCallsLink extends Cornac_Auditeur_AnalyzerDot {
	protected	$title = 'Functions related by call';
	protected	$description = 'List of functioncalls, from within another function';

	public function analyse() {
        $this->cleanReport();
        
        $in = join("', '", Cornac_Auditeur_Analyzer::getPHPFunctions()); 
        $concat1 = $this->concat("T1.class","'->'","T1.scope");
        $concat2 = $this->concat("T3.code","'->'","T4.code");
        $query = <<<SQL
SELECT $concat1, $concat2, T1.file, '{$this->name}'
FROM <tokens> T1
JOIN <tokens_cache> T2 
    ON T1.id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file     AND
       T3.left = T1.left + 1 AND
       T3.code != '\$this'
JOIN <tokens> T4
    ON T1.file = T4.file     AND
       T4.left = T1.left + 4
WHERE  T1.type='method_static'
SQL;
        $res = $this->execQueryInsert('report_dot', $query);

        $concat1 = $this->concat("T1.class","'->'","T1.scope");
        $concat2 = $this->concat("T1.class","'->'","T4.code");
        $query = <<<SQL
SELECT $concat1, $concat2, T1.file, '{$this->name}'
FROM <tokens> T1
JOIN <tokens_cache> T2 
    ON T1.id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file     AND
       T3.left = T1.left + 1 AND
       T3.code = '\$this'
JOIN <tokens> T4
    ON T1.file = T4.file     AND
       T4.left = T1.left + 4
WHERE T1.type='method' 
SQL;
        $res = $this->execQueryInsert('report_dot', $query);

        $query = <<<SQL
SELECT T4.code AS method, T1.class AS classe
FROM <tokens> T1
JOIN <tokens_cache> T2 
    ON T1.id = T2.id
JOIN <tokens> T3
    ON T1.file = T3.file     AND
       T3.left = T1.left + 1 AND
       T3.code != '\$this'
JOIN <tokens> T4
    ON T1.file = T4.file     AND
       T4.left = T1.left + 4
WHERE T1.type='method'
SQL;
        $res = $this->execQuery($query);
        
        $erreurs = 0;
        $total = 0;
        while($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $query = <<<SQL
SELECT T1.element
FROM <report> T1
WHERE  T1.module='defmethodes'                   AND 
       T1.element NOT LIKE "{$row["classe"]}->%" AND
       T1.element LIKE "%->{$row["method"]}"
SQL;
            $res2 = $this->execQuery($query);            
            
            if ($res2->rowCount() == 0) {
                $erreurs++;
            }
            $total++;
        }
        return true;
    }
}

?>