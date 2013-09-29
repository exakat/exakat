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

class Cornac_Auditeur_Analyzer_Functions_Unused extends Cornac_Auditeur_Analyzer {
    protected $title = 'Unused functions'; 
    protected $description = 'List of unused functions'; 

	
	function dependsOn() {
	    return array('Structures_FunctionsCalls','Functions_Definitions');
	}
	
	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, TR1.file, TR1.element AS code, TR1.id, '{$this->name}', 0
FROM <report> TR1
LEFT JOIN <report>  TR2 
ON TR1.element = TR2.element AND 
   TR2.module='Structures_FunctionsCalls' 
WHERE TR1.module = 'Functions_Definitions' AND 
      TR2.module IS NULL AND
      TR1.element NOT IN ('__autoload')
SQL;
        $this->execQueryInsert('report', $query);
        return true;
	}
}

?>