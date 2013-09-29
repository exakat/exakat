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


class Cornac_Auditeur_Analyzer_Classes_InterfacesUnused extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused Interfaces';
	protected	$description = 'List useless interfaces.';


	function dependsOn() {
	    return array('Classes_InterfacesUsed','Classes_Interfaces');
	}

	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, T1.element, T1.id, '{$this->name}', 0
FROM <report> T1
LEFT JOIN <report> T2
    ON T2.module = 'Classes_InterfacesUsed' AND
       T2.element = T1.element
WHERE T1.module = 'Classes_Interfaces' AND
      T2.id IS NULL
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>