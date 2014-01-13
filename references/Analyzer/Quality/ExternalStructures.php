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

class Cornac_Auditeur_Analyzer_Quality_ExternalStructures extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Common libraries\'s structures';
	protected	$description = 'Spot commonly used classes, constants, functions from libraries.';

	
	function dependsOn() {
	    return array('Constants_Usage', 'Classes_News','Structures_FunctionsCalls');
	}

	public function analyse() {
        $this->cleanReport();
// @todo spot versions also ? 

        $list = Cornac_Auditeur_Analyzer::getPopLib();
        
        foreach($list as $ext => $characteristics) {

            if (isset($characteristics['classes'])) {
                $in = "'".join("', '", $characteristics['classes'])."'";

            // @doc search for usage by class extensions
                $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.type='_classname_' AND
      T1.code IN ($in)
SQL;
                $this->execQueryInsert('report', $query);

            // @doc search for usage by instanciation
                $query = <<<SQL
SELECT NULL, TR.file, TR.element, TR.id, '{$this->name}', 0
FROM <report> TR
WHERE TR.module = 'Classes_News' AND 
      TR.element IN ($in)
SQL;
                $this->execQueryInsert('report', $query);
            }

            // @doc search for usage by functioncall
            if (isset($characteristics['functions'])) {
                $in = "'".join("', '", $characteristics['functions'])."'";
                $query = <<<SQL
SELECT NULL, TR.file, TR.element, TR.id, '{$this->name}', 0
FROM <report> TR
WHERE TR.module = 'Structures_FunctionsCalls' AND 
      TR.element IN ($in)
SQL;
                $this->execQueryInsert('report', $query);
            }

            // @doc search for usage by constants usage
            if (isset($characteristics['constants'])) {
                $in = "'".join("', '", $characteristics['constants'])."'";
                $query = <<<SQL
SELECT NULL, TR.file, TR.element, TR.id, '{$this->name}', 0
FROM <report> TR
WHERE TR.module = 'Constants_Usage' AND 
      TR.element IN ($in)
SQL;
                $this->execQueryInsert('report', $query);
            }

        }

        return true;
	}
}

?>