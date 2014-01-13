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


class Cornac_Auditeur_Analyzer_Zf_Db extends Cornac_Auditeur_Analyzer {
	protected	$title = 'ZF : usage of Zend_Db API';
	protected	$description = 'Usage of where() method';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT 
    ON TT.token_id = T1.id AND 
       TT.type='method'
JOIN <tokens> T2
    ON T1.file = T2.file AND
       TT.token_sub_id = T2.id AND
       T2.code = 'where'
JOIN <tokens_cache> TC
    ON T1.id = TC.id
WHERE T1.type = 'method'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>