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

class Cornac_Auditeur_Analyzer_Functions_DoubleDeclaration extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Functions defined twice';
	protected	$description = 'List functions being defined twice, at least. Hopefully, no one will try to use them simultaneously.';

	
	function dependsOn() {
        return array('Functions_Definitions');	
	}

	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, file, TR.element,  TR.token_id, '{$this->name}', 0
FROM <report> TR
WHERE module='Functions_Definitions'
GROUP BY file, element 
HAVING COUNT(*) > 1
SQL;
    
        $this->execQueryInsert('report', $query);
        return true;
	}
}

?>