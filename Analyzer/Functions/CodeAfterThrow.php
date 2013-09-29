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


class Cornac_Auditeur_Analyzer_Functions_CodeAfterThrow extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Dead code after throw';
	protected	$description = 'Spot dead code after throw. ';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT(T1.class, "::",T1.scope), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file = T2.file AND
       T1.left BETWEEN T2.left AND T2.right AND
       T2.type='_function'
WHERE T1.type='_throw' AND
      T2.right != T1.right + 2 AND 
      T2.level = T1.level - 2
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>