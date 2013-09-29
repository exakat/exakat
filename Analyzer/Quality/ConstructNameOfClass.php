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


class Cornac_Auditeur_Analyzer_Quality_ConstructNameOfClass extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Class constructors';
	protected	$description = 'Spot classes constructor (__construct, NameOfClasse)';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, concat(class, '::', T1.code), T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE type = '_function' AND
      (code = class        OR
       code = '__construct'  )
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>