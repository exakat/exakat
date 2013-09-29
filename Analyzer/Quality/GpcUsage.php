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

class Cornac_Auditeur_Analyzer_Quality_GpcUsage extends Cornac_Auditeur_Analyzer {
	protected	$title = 'GPC arrays';
	protected	$description = 'Usage of PHP super global array (GPC, GLOBALS, SESSION, FILES, etc)';

	
	public function analyse() {
        $this->cleanReport();

// @note cas simple : variable -> method
        $query = <<<SQL
SELECT NULL, T1.file, T1.code AS code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
LEFT JOIN <tokens> T2 
    ON T1.left - 1 = T2.left AND 
       T1.file = T2.file
WHERE T1.type="variable" AND
      T1.code IN ('\$_GET','\$_SERVER','\$GLOBALS','\$_POST','\$_REQUEST','\$_ENV','\$_COOKIE','\$_SESSION') AND 
      T2.type != '_array'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>