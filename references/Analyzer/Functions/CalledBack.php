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

class Cornac_Auditeur_Analyzer_Functions_CalledBack extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Callback Functions';
	protected	$description = 'Name of callback functions used with PHP functions that needs a call back. ie : array_map("callback_function",$some_array) will spot "callback_function".';


	function dependsOn() {
	    return array('Functions_Php',);
	}
	
	public function analyse() {
// @todo spot functions when it is a method call (aka, it is an array instead of a function) 
        $this->cleanReport();

        $functions = array();
        // @note callback is in second position
        $functions[1] = array('array_map',
                              'call_user_func',
                              'call_user_func_array');

        $in = "'".join("', '", $functions[1])."'";

	    $query = <<<SQL
SELECT NULL, TR1.file, T2.code, T2.id, '{$this->name}', 0
FROM <report> TR1
JOIN <tokens> T1
ON T1.file = TR1.file AND
   T1.id = TR1.token_id
JOIN <tokens> T2
ON T1.file = T2.file AND
   T2.left = T1.left + 4
WHERE TR1.module="Functions_Php" AND 
      TR1.element IN ($in)
SQL;
        $this->execQueryInsert('report', $query);

        // callback is in second position
        $functions[2] = array('usort', 
                              'preg_replace_callback',
                              'uasort',
                              'uksort',
                              'array_reduce',
                              'array_walk',
                              'array_walk_recursive',
                              'mysqli_set_local_infile_handler',
                              );
        $in = "'".join("', '", $functions[2])."'";

	    $query = <<<SQL
SELECT NULL, TR1.file, T2.code, T2.id, '{$this->name}', 0
FROM <report> TR1
JOIN <tokens> T1
ON T1.file = TR1.file AND
   T1.id = TR1.token_id
JOIN <tokens> T2
ON T1.file = T2.file AND
   T2.left = T1.left + 4 + 2
WHERE TR1.module="Functions_Php" AND 
      TR1.element IN ($in)
SQL;
        $this->execQueryInsert('report', $query);

        // callback is in last position
        $functions[-1] = array('array_diff_uassoc',
                               'array_diff_ukey',
                               'array_intersect_uassoc',
                               'array_intersect_ukey',
                               'array_udiff_assoc',
                               'array_udiff_uassoc',
                               'array_udiff',
                               'array_uintersect_assoc',
                               'array_uintersect_uassoc',
                               'array_uintersect',
                               'array_filter',
                               'array_reduce',
                            );

        $functions = "'".join("', '", $functions[-1])."'";

	    $query = <<<SQL
SELECT NULL, TR1.file, T2.code, T2.id, '{$this->name}', 0
FROM <report> TR1
JOIN <tokens> T1
ON T1.file = TR1.file AND
   T1.id = TR1.token_id
JOIN <tokens> T2
ON T1.file = T2.file AND
   T2.right = T1.right - 2
WHERE TR1.module="Functions_Php" AND 
      TR1.element IN ($functions)
SQL;
        $this->execQueryInsert('report', $query);

        
        return true;
	}
}

?>