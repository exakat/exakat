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

class Cornac_Auditeur_Analyzer_Quality_FilesMultipleDefinition extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Multi declaration files';
	protected	$description = 'Files that declare several structures (classes, functions and global code).';

	
	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
CREATE TEMPORARY TABLE Quality_FilesMultipleDefinition
SELECT DISTINCT T1.file AS file,  if (class= '', scope, class) AS context
FROM <tokens> T1
WHERE T1.type NOT IN ('codephp','sequence')
SQL;
        $res = $this->execQuery($query);

	    $query = <<<SQL
SELECT NULL, T1.file, T1.context, 0, '{$this->name}', 0
FROM Quality_FilesMultipleDefinition T1
SQL;
        $res = $this->execQueryInsert('report', $query);

	    $query = <<<SQL
DROP TABLE Quality_FilesMultipleDefinition
SQL;
        $res = $this->execQuery($query);

        return true;
    }
}

?>