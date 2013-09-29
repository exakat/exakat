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

class Cornac_Auditeur_Analyzer_Classesusage extends Cornac_Auditeur_Analyzer {

	public function analyse() {
        $this->cleanReport();

// @note heritage 
// @todo isnt't this missing 2+ level heritages? Needs a while here.
	    $query = <<<SQL
SELECT NULL, T1.file, T2.code AS code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT 
    ON T1.id = TT.token_id     AND
       TT.type='extends'
JOIN <tokens> T2
    ON T2.id = TT.token_sub_id AND
       T2.file=T1.file
WHERE T1.type = '_class'       AND
      T2.code IN ("{$this->in}")
SQL;
        $this->execQueryInsert('report', $query);

// @note direct instantiation with new
        $query = <<<SQL
SELECT NULL, T1.file, T2.code AS code, T2.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.left = T1.left + 1
WHERE T1.type='_new' AND
      T2.code IN ("{$this->in}")
SQL;
        $this->execQueryInsert('report', $query);

// @note static usage
        $query = <<<SQL
SELECT NULL, T1.file, T2.code AS code, T2.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.left = T1.left + 1
WHERE T1.type='method_static' AND
      T2.code IN ("{$this->in}")
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}
?>