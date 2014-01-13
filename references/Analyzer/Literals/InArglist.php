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


class Cornac_Auditeur_Analyzer_Literals_InArglist extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Literals used as arguments';
	protected	$description = 'Literal values passed as argument of function, when the former expect';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
DROP TABLES IF EXISTS Literals_InArglist_calls, Literals_InArglist_definitions
SQL;
        $this->execQuery($query);
// @todo drop the above

	    $query = <<<SQL
SELECT @i := 0;
SQL;
        $this->execQuery($query);

	    $query = <<<SQL
SELECT @id := 0;
SQL;
        $this->execQuery($query);

// @todo make temporary
	    $query = <<<SQL
CREATE TABLE Literals_InArglist_definitions
SELECT  T1.file AS file, 
        T4.class AS class, 
        T4.scope AS scope, 
        T3.type,
        T3.code,
       if (@id = T2.id, @i := @i + 1, LEAST(@id := T2.id , @i := 0 )) AS rank
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND 
       T2.left = T1.left + 3 AND
       T2.type = 'arglist'
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.level = T2.level + 1 AND
       T3.left BETWEEN T2.left AND T2.right
JOIN <tokens> T4
    ON T4.file = T1.file AND
       T4.left = T1.left + 1
WHERE T1.type='_function'
SQL;
        $this->execQuery($query);

// @note process only functions (not methods yet)
	    $query = <<<SQL
SELECT GROUP_CONCAT(distinct scope SEPARATOR "','") AS list 
FROM Literals_InArglist_definitions 
WHERE class=''
SQL;
        $res = $this->execQuery($query);
        $rows = $res->fetch(PDO::FETCH_ASSOC);
        $in = "'".$rows['list']."'";

	    $query = <<<SQL
SELECT @i := 0;
SQL;
        $this->execQuery($query);

	    $query = <<<SQL
SELECT @id := 0;
SQL;
        $this->execQuery($query);

// @todo make TEMPORARY
	    $query = <<<SQL
CREATE TEMPORARY TABLE Literals_InArglist_calls
SELECT T3.file, 
       T1.code, 
       T2.id, 
       T4.type, 
       if (@id = T3.id, @i := @i + 1, LEAST(@id := T3.id , @i := 0 )) AS rank
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file     AND
       T2.left = T1.left - 1 AND
       T2.type = 'functioncall'
JOIN <tokens> T3
    ON T3.file = T1.file      AND
       T3.left = T1.right + 1 AND
       T3.type = 'arglist'
JOIN <tokens> T4
    ON T4.file = T1.file       AND
       T4.level = T3.level + 1 AND
       T4.left BETWEEN T3.left AND T3.right
WHERE T1.code IN ($in)
SQL;
        $this->execQuery($query);

	    $query = <<<SQL
DROP TABLES Literals_InArglist_calls, Literals_InArglist_definitions
SQL;
        $this->execQuery($query);

// @todo this is not doing any insertion in table report!
        return true;
	}
}

?>