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


class Cornac_Auditeur_Analyzer_Zf_FormElementNew extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Zend_FormElement instanciated';
	protected	$description = 'List elements instantiated. ';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array('Classes_News', 'Zf_FormElement');
	}

	public function analyse() {
        $this->cleanReport();

// @doc classes instantiated from herited classes
	    $query = <<<SQL
SELECT NULL, TR1.file, TR1.element, TR1.token_id, '{$this->name}', 0
FROM <report> TR1
JOIN <report> TR2
    ON TR1.module = 'Classes_News' AND
       TR2.module = 'Zf_FormElement' AND
       TR1.element = TR2.element
SQL;
        $this->execQueryInsert('report', $query);

// @doc classes directly instantiated from Zend
	    $query = <<<SQL
SELECT NULL, TR1.file, TR1.element, TR1.token_id, '{$this->name}', 0
FROM <report> TR1
WHERE TR1.module = 'Classes_News' AND
      TR1.element LIKE "Zend_Form_Element%"
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>