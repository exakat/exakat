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


class Cornac_Auditeur_Analyzer_Functions_Relay extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Relay functions';
	protected	$description = 'Functions that relay they argument to another function else, and won\'t do anything besides.';

	public function analyse() {
        $this->cleanReport();

// @todo remove at least one of the temporary table
	    $query = <<<SQL
CREATE TEMPORARY TABLE Functions_Relay_Calls
SELECT T1.id, T1.code, GROUP_CONCAT(T3.code ORDER BY T3.code) AS args, T1.scope
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'args'
JOIN <tokens> T2
    ON T2.file = T1.file AND
       TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.type = 'variable'
WHERE T2.type = 'arglist' AND
      T1.type = 'functioncall'
GROUP BY T1.id
SQL;
        $this->execQuery($query);

	    $query = <<<SQL
CREATE TEMPORARY TABLE Functions_Relay_Definitions
SELECT T1.id, T1.code, T1.file, GROUP_CONCAT(T3.code ORDER BY T3.code) AS args, T1.scope
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'args'
JOIN <tokens> T2
    ON T2.file = T1.file AND
       TT.token_sub_id = T2.id
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.type = 'variable'
WHERE T2.type = 'arglist' AND
      T1.type = '_function'
GROUP BY T1.id
SQL;
        $this->execQuery($query);

	    $query = <<<SQL
SELECT NULL, def.file, def.code AS code, def.id, '{$this->name}', 0
FROM Functions_Relay_Definitions def
JOIN Functions_Relay_Calls calls
    ON def.args = calls.args AND
       calls.scope = def.code
SQL;
        $this->execQueryInsert('report', $query);

        $query = <<<SQL
DROP TABLE Functions_Relay_Definitions, Functions_Relay_Calls
SQL;
        $this->execQuery($query);

        return true;
	}
}

?>