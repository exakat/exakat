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

class Cornac_Auditeur_Analyzer_Quality_Indenting extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Indentations';
	protected	$description = 'List of indentation level expected. We expect a new level with the following scopes : classes, functions, loops and switch.';


	public function analyse() {
        $this->cleanReport();

/* @example
+---------+----------+--------------------------------------------------------------+
| id      | COUNT(*) | GROUP_CONCAT(P.type ORDER BY P.left)                         |
+---------+----------+--------------------------------------------------------------+
| 1754692 |        1 | ifthen                                                       |
| 1754718 |        1 | ifthen                                                       |
| 1754765 |        1 | ifthen                                                       |
| 1754802 |        2 | ifthen,ifthen                                                |
| 1754897 |        2 | ifthen,ifthen                                                |

*/
        $query = <<<SQL
SELECT NULL, N.file, GROUP_CONCAT(P.type ORDER BY P.left) AS code, N.id, '{$this->name}', 0
FROM <tokens> P
JOIN <tokens> N
    ON P.type IN ('ifthen','_class','_function','_while','_dowhile','_foreach','_case','_for','_switch') AND 
       N.left BETWEEN P.left AND P.right AND
       N.type IN ('ifthen','_class','_function','_while','_dowhile','_foreach','_case','_for','_switch') AND
       N.file = P.file
GROUP BY N.id
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>