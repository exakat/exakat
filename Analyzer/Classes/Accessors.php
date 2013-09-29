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

class Cornac_Auditeur_Analyzer_Classes_Accessors extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Accessors';
	protected	$description = 'List of class accessors : must be using prefix get or set, followed by the name of a declared property (case insensitive, works on hierarchy, up to two levels)';

	function dependsOn() {
	    return array('Classes_MethodsDefinition', 'Classes_Properties' );
	}

	public function analyse() {
        $this->cleanReport('Classes_Properties','Classes_Hierarchy','Classes_MethodsDefinition');

	    $query = <<<SQL
SELECT NULL, T1.file, T2.element, T1.id, '{$this->name}' , 0
FROM <report> T1
JOIN <report> T2
    ON T2.file = T1.file AND
       (replace(T1.element, '$', 'get') = T2.element OR 
        replace(T1.element, '$', 'set') = T2.element ) AND
       T2.module = 'Classes_MethodsDefinition'
WHERE T1.module = 'Classes_Properties'
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT NULL, T1.file, T2.element, T1.id, '{$this->name}' , 0
FROM <report> T1
JOIN <report_dot> TD1
    ON left(T1.element, locate('->',T1.element) - 1) = TD1.a AND
       TD1.module = 'Classes_Hierarchy'
JOIN <report> T2
    ON T2.file = T1.file AND
       (concat(TD1.b,'->get',right(T1.element, length(T1.element) - 3 - length(TD1.b))) = T2.element OR 
        concat(TD1.b,'->set',right(T1.element, length(T1.element) - 3 - length(TD1.b))) = T2.element   ) AND
       T2.module = 'Classes_MethodsDefinition'
WHERE T1.module = 'Classes_Properties'
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT NULL, T1.file, T2.element, T1.id, '{$this->name}' , 0
FROM <report> T1
JOIN <report_dot> TD1
    ON left(T1.element, locate('->',T1.element) - 1) = TD1.a AND
       TD1.module = 'Classes_Hierarchy'
JOIN <report_dot> TD2
    ON TD2.a = TD1.b AND
       TD2.module = 'Classes_Hierarchy'
JOIN <report> T2
    ON T2.file = T1.file AND
       (concat(TD2.b,'->get',right(T1.element, length(T1.element) - 3 - length(TD1.b))) = T2.element OR 
        concat(TD2.b,'->set',right(T1.element, length(T1.element) - 3 - length(TD1.b))) = T2.element   ) AND
       T2.module = 'Classes_MethodsDefinition'
WHERE T1.module = 'Classes_Properties'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>