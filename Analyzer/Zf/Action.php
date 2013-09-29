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

class Cornac_Auditeur_Analyzer_Zf_Action extends Cornac_Auditeur_Analyzer {
	protected	$title = 'ZF : action';
	protected	$description = 'List of methods for the Zend Framework ';

	
	public function analyse() {
        $this->cleanReport();

        if (isset($this->ini['classes'])) {
            if (is_array($this->ini['classes']) ) {
                $classes = ', "'.join('", "', explode(',',$this->ini['classes'])).'"';
            } else {
                $classes = ", '{$this->ini['classes']}' ";
            }
        } else {
            $classes = "";
        }

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT(T1.class,'::',T1.code), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN  <tokens_tags> TT
    ON TT.token_sub_id = T1.id
JOIN  <tokens> T2
ON T2.file = T1.file AND
   T1.left BETWEEN T2.left AND T2.right AND
   T2.type = '_class'
JOIN  <tokens_tags> TT2
ON TT2.token_id = T2.id AND
   TT2.type = 'extends'
JOIN  <tokens> T3
ON T3.file = T1.file AND
   TT2.token_sub_id = T3.id
WHERE 
    T1.code LIKE "%Action" AND 
    TT.type = 'name' AND
    T3.code IN ( "Application_Zend_Controller","Zend_Controller" $classes)
SQL;
        $this->execQueryInsert('report',$query);
        
        return true;
	}
}

?>