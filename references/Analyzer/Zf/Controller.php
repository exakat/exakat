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

class Cornac_Auditeur_Analyzer_Zf_Controller extends Cornac_Auditeur_Analyzer {
	protected	$title = 'ZF : controllers';
	protected	$description = 'List of *Action methods from the ZF';

	
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

        $concat = $this->concat("T3.class", "'->'","T3.code");
	    $query = <<<SQL
SELECT NULL, T1.file, $concat AS code, T3.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT 
ON T1.id = TT.token_id AND
   TT.type='extends'
JOIN <tokens> T2
ON T2.id = TT.token_sub_id AND
   T2.file=T1.file
JOIN <tokens> T3
ON T3.file = T2.file AND 
   T3.left BETWEEN T1.left AND T1.right AND
   T3.type = '_function'
WHERE T1.type = '_class' AND
    T2.code IN ( "Application_Zend_Controller","Zend_Controller" $classes) AND
    T3.code LIKE "%Action"
SQL;
        $this->execQueryInsert('report', $query);
        
        return true;
	}
}

?>