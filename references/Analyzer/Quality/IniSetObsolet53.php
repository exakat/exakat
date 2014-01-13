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


class Cornac_Auditeur_Analyzer_Quality_IniSetObsolet53 extends Cornac_Auditeur_Analyzer {
	protected	$title = 'PHP directive obsolete in 5.3';
	protected	$description = 'PHP directive obsolete in 5.3';


	public function analyse() {
        $this->cleanReport();

// @todo of course, update this useless query. :)
	    $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.left = T1.right + 2 AND
       T2.file = T1.file
WHERE T1.type = '_functionname_' AND
      T1.code IN ( 'ini_set', 'ini_get' ) AND
      T2.code IN ('define_syslog_variables',
                  'register_globals',
                  'register_long_arrays',
                  'safe_mode',
                  'magic_quotes_gpc',
                  'magic_quotes_runtime',
                  'magic_quotes_sybase')
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>