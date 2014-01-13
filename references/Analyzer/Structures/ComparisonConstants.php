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


class Cornac_Auditeur_Analyzer_Structures_ComparisonConstants extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Static comparison';
	protected	$description = 'Comparison that have no variable part, nor is trying to guess the current PHP installation.';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type IN ('right','left')
JOIN <tokens> T2
    ON T1.file = T2.file AND
       T2.id = TT.token_sub_id
JOIN <tokens_cache> TC
    ON T1.id = TC.id
WHERE T1.type IN ( 'logical','comparison') AND
      T2.type IN ('_constant','literals')
GROUP BY T1.id
HAVING COUNT(*) = 2
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>