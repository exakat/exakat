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


class Cornac_Auditeur_Analyzer_Zf_TypeView extends Cornac_Auditeur_Analyzer {
	protected	$title = 'ZF : style of view';
	protected	$description = 'List what kind of view handles the exit of those ';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
LEFT JOIN <tokens> T2
ON T2.file = T1.file AND
   T2.left BETWEEN T1.left AND T1.right AND
   T2.code IN ('json','encode','readfile','echo','removeViewRenderer','_redirect','redirect','die','exit','render') AND
   T2.type='functioncall'
WHERE T1.code LIKE "%Action" AND 
      T1.type = '_function'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>