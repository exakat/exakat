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
class Cornac_Auditeur_Analyzer_Functions_ArglistReferences extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Fonctions avec références';
	protected	$description = 'Fonctions et méthodes avec références';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT(T1.class,'::', T1.scope), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.level = T1.level + 1 AND
       T2.left BETWEEN T1.left AND T1.right AND
       T2.type = 'reference'
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left = T2.left + 1
WHERE T1.type = 'arglist'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>