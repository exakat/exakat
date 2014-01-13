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


class Cornac_Auditeur_Analyzer_Quality_GpcAsArgument extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Gpc passed as argument';
	protected	$description = 'Spot GPC variables used as argument of other functions (this is a good way to hide Gpc usage, but leads to security problems).';


	public function analyse() {
        $this->cleanReport();

// @todo of course, update this useless query. :)
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file = T2.file AND
       T2.left BETWEEN T1.left AND T2.right AND
       T2.level = T1.level + 1 AND
       T2.type = 'variable' AND
       T2.code LIKE "$\_%"
JOIN <tokens_tags> TT
    ON TT.token_sub_id = T1.id AND
       TT.type = 'args'
JOIN <tokens> T3
    ON TT.token_id = T3.id
JOIN <tokens_cache> TC
    ON TC.id = T3.id
WHERE T1.type = 'arglist'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>