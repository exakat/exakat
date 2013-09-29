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


class Cornac_Auditeur_Analyzer_Structures_FluentProperties extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Fluent interfaces with properties';
	protected	$description = 'Spot long chaining of properties call ($x->y->z->a->b->c).';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT(beginning.code, '->', GROUP_CONCAT(T3.code ORDER BY T3.left  SEPARATOR '->')) , T1.id, '{$this->name}',0
FROM <tokens> T1
INNER JOIN (
    SELECT T3.id, T1.file, T3.left, T3.right, T3.code
    FROM <tokens> T1
    JOIN <tokens> T2
    ON T1.file = T2.file AND
       T2.type = 'property' AND
       T2.left = T1.left - 1
    JOIN <tokens> T3
    ON T1.file = T3.file AND
       T3.type = 'variable' AND
       T3.left = T1.left + 1
    WHERE T1.type = 'property'
) beginning
ON T1.file = beginning.file AND
   type='property' AND 
   T1.left < beginning.left AND 
   T1.right > beginning.right
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type='property'
JOIN <tokens> T3
    ON T3.file = beginning.file AND
       T3.id = TT.token_sub_id
GROUP BY beginning.id
HAVING COUNT(*) > 1
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>