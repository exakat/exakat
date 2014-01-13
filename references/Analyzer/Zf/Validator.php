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


class Cornac_Auditeur_Analyzer_Zf_Validator extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Zend Validator classes';
	protected	$description = 'Spot classes that extends Zend_Validate ';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array('Classes_Hierarchy');
	}

	public function analyse() {
        $this->cleanReport();

// @todo searching for herited classes from a framework is a common task. Make this a generic class 

// @doc herited usage of Zend Framework element (one heritage)
	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <report_dot> TD
JOIN <tokens> T1
    ON T1.code = TD.b AND
       T1.type = '_classname_'
WHERE TD.a LIKE "Zend_Validate%" AND
      TD.module = 'Classes_Hierarchy'
SQL;
        $this->execQueryInsert('report', $query);

// @doc herited usage of Zend Framework element (2nd level heritage)
	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <report_dot> TD
JOIN <report_dot> TD2
    ON TD.b = TD2.a AND
       TD2.module = 'Classes_Hierarchy'
JOIN <tokens> T1
    ON T1.code = TD2.b AND
       T1.type = '_classname_'
WHERE TD.a LIKE "Zend_Validate%" AND
      TD.module = 'Classes_Hierarchy'
SQL;
        $this->execQueryInsert('report', $query);

// @doc herited usage of Zend Framework element (3rd level heritage)
	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <report_dot> TD
JOIN <report_dot> TD2
    ON TD.b = TD2.a AND
       TD2.module = 'Classes_Hierarchy'
JOIN <report_dot> TD3
    ON TD2.b = TD3.a AND
       TD3.module = 'Classes_Hierarchy'
JOIN <tokens> T1
    ON T1.code = TD3.b AND
       T1.type = '_classname_'
WHERE TD.a LIKE "Zend_Validate%" AND
      TD.module = 'Classes_Hierarchy'
SQL;
        $this->execQueryInsert('report', $query);
 
// @doc herited usage of Zend Framework element (4th level heritage)
	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <report_dot> TD
JOIN <report_dot> TD2
    ON TD.b = TD2.a AND
       TD2.module = 'Classes_Hierarchy'
JOIN <report_dot> TD3
    ON TD2.b = TD3.a AND
       TD3.module = 'Classes_Hierarchy'
JOIN <report_dot> TD4
    ON TD3.b = TD4.a AND
       TD4.module = 'Classes_Hierarchy'
JOIN <tokens> T1
    ON T1.code = TD3.b AND
       T1.type = '_classname_'
WHERE TD.a LIKE "Zend_Validate%" AND
      TD.module = 'Classes_Hierarchy'
SQL;
        $this->execQueryInsert('report', $query);

// @todo 5th level heritage ? 

        return true;
	}
}

?>