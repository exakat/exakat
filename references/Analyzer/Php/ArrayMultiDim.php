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

class Cornac_Auditeur_Analyzer_Php_ArrayMultiDim extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Multi-dimensionnal arrays';
	protected	$description = 'List of arrays that are multidimensionnal : $x[1][2], $x[1][2][3], and more.';

	public function analyse() {
        $this->cleanReport();

// @note the comment /* JOIN */ here is important
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code ,T1.id, '{$this->name}', 0
FROM <tokens> T1
/* JOIN */
JOIN <tokens_cache> TC
    ON TC.id = T1.id
LEFT JOIN <tokens> TX
    ON TX.type IN ('_array','opappend') AND 
       T1.file = TX.file AND
       T1.left - 1 = TX.left
LEFT JOIN <report> TR
    ON TR.module='{$this->name}' AND
       TR.token_id = T1.id
WHERE T1.type IN ('_array','opappend') AND
      TR.id IS NULL AND
      TX.id IS NULL
SQL;

for($i = 2; $i < 7; $i++) {
    $h = $i - 1;
    $join = <<<SQL
JOIN <tokens> T$i
    ON T$i.type IN ('_array','opappend') AND 
       T1.file = T$i.file AND
       T$h.left + 1 = T$i.left
/* JOIN */
SQL;
    $query = str_replace('/* JOIN */', $join, $query);
    $query = str_replace('       T'.$h.'.left + 1 = TX.left','       T'.$i.'.left + 1 = TX.left', $query);

    $this->execQueryInsert('report', $query);
}

        // @todo spot array(array());
        
        return true;
	}
}

?>