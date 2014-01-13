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

class Cornac_Auditeur_Analyzer_Functions_Definitions extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Function definitions';
	protected	$description = 'Function definitions';

	
	public function analyse() {
        $this->cleanReport();

        $this->hooks = Cornac_Auditeur_Analyzer::getDrupal7Hooks();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_function')
                      ->reportCode()
                      ->_class('');
        $this->backend->run('attributes');
/*
        $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON T1.id = TT.token_id  
JOIN <tokens> T2 
    ON TT.token_sub_id = T2.id
WHERE T1.type='_function'      AND 
      TT.type = 'name' AND
      T1.class = '';
SQL;
    
        $this->execQueryInsert('report', $query);
*/        return false;
	}
}

?>