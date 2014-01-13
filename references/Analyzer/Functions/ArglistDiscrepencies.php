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

class Cornac_Auditeur_Analyzer_Functions_ArglistDiscrepencies extends Cornac_Auditeur_Analyzer {
	protected	$title = "Too many arguments function call";
	protected	$description = 'Function call that are providing too many arguments, compared to function definition';


	function dependsOn() {
	    return array('Functions_ArglistDefined','Functions_ArglistCalled');
	}

	public function analyse() {
        $this->cleanReport();
        $query = <<<SQL
SELECT NULL, TR1.file, TR1.element AS code, TR1.id, '{$this->name}', 0
FROM <report> TR1
LEFT JOIN <report> TR2
    ON TR2.module='Functions_ArglistDefined' AND
       LEFT(TR1.element, locate('(', TR1.element) - 1) = LEFT(TR2.element, locate('(', TR2.element) -1) AND
       TR1.element = TR2.element
WHERE TR1.module = 'Functions_ArglistCalled' AND 
      TR2.element IS NULL
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>