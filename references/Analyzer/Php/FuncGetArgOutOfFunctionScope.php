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


class Cornac_Auditeur_Analyzer_Php_FuncGetArgOutOfFunctionScope extends Cornac_Auditeur_Analyzer {
	protected	$title = 'func_get_arg out of function scope';
	protected	$description = 'func_get_arg out of function scope. This was OK before 5.3, and not anymore.';

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.type = '_functionname_' AND
      T1.code IN ('func_get_arg','func_get_args','func_num_args') AND
      T1.scope = 'global' AND
      T1.class = ''
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>