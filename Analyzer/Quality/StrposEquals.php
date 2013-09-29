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


class Cornac_Auditeur_Analyzer_Quality_StrposEquals extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Strpos ==';
	protected	$description = 'Strpos() should be used with ==, or finding the string in position 0 will go undetected.';
    protected    $tags = array('quality');


	public function analyse() {
        $this->cleanReport();

        $in = "'strpos', 'stripos','strrpos','strtok'";
// @note strpos == 0 or 0 == strpos
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id,'{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type IN ('right','left')
JOIN <tokens> T2
    ON T1.file = T2.file AND
       T2.id = TT.token_sub_id AND
       ((T2.type = 'literals' AND T2.code = 0) OR
        (T2.type = 'functioncall') AND
         T2.code IN ($in))
JOIN <tokens_cache> TC
    ON T1.id = TC.id
WHERE T1.type = 'comparison' AND
      T1.code = '=='
GROUP BY T1.id
HAVING COUNT(*) = 2
SQL;
        $this->execQueryInsert('report', $query);

// @note (strpo()) (direct in parenthesis, used for if
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id,'{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.left = T1.left + 1 AND
       T2.type = 'functioncall' AND
       T2.code IN ($in)
JOIN <tokens_cache> TC
    ON T2.id = TC.id
WHERE T1.type='parenthesis'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>