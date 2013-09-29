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

class Cornac_Auditeur_Analyzer_Structures_IfWithoutElse extends Cornac_Auditeur_Analyzer {
	protected	$title = 'If sans else';
	protected	$description = 'Liste des if sans else';

	
	public function analyse() {
        $this->cleanReport();

        $concat = $this->concat("T2.class","'->'","T2.code");
	    $query = <<<SQL
SELECT NULL, T1.file, SUM(TT.type = 'else')  AS elsee, T1.id, '{$this->name}', 0
FROM <tokens> T1
LEFT join <tokens_tags> TT 
    ON T1.id = TT.token_id
WHERE T1.type = 'ifthen' 
GROUP BY file, `left`
HAVING elsee = 0
SQL;
        $this->execQueryInsert('report', $query);
        
        return true;
	}
}

?>