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

class Cornac_Auditeur_Analyzer_Classes_News extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Usage of new';
	protected	$description = 'Use of new operator in the code. List classes names being instantiated.';

	
	public function analyse() {
        $this->cleanReport();

// @note new with literals 
        $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens>  T1
JOIN <tokens> T2
   ON T1.left + 1 = T2.left AND 
      T1.file = T2.file AND
      T2.type IN ('_classname_','variable')
WHERE T1.type = '_new'
SQL;
        $this->execQueryInsert('report', $query);

// @note new with variables 
        $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens>  T1
JOIN <tokens> T2
   ON T1.left + 1 = T2.left AND 
      T1.file = T2.file AND
      T2.type NOT IN ('_classname_','variable')
JOIN <tokens_cache> TC
   ON TC.id = T2.id
WHERE T1.type = '_new'
SQL;
        $this->execQueryInsert('report', $query);

	}
}

?>