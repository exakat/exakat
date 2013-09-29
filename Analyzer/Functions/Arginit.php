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


class Cornac_Auditeur_Analyzer_Functions_Arginit extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Arguments initialisations';
	protected	$description = 'Arguments initialisations';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT T3.id 
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.left = T1.left + 5
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.left BETWEEN T2.left AND T2.right AND
       T3.level = T2.level + 1 AND
       T3.type = 'affectation'
WHERE T2.type = 'arglist' AND
      T1.type = '_function'
SQL;
        $this->execQueryAttributes($this->name, $query);

        return true;
	}
}

?>