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

class Cornac_Auditeur_Analyzer_Php_Modules extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Needed PHP extensions';
	protected	$description = 'List of needed PHP extensions. Functions, Constants and classes are checked.';

	function dependsOn() {
	    return array('Functions_Php',
	                 'Classes_Php',
	                 'Classes_MethodsCall');
	}
	
	public function analyse() {
        $this->cleanReport();

        $this->functions = Cornac_Auditeur_Analyzer::getPHPFunctions();
	    
	    // @section : searching via functions usage
	    $query = <<<SQL
SELECT NULL, TR.file, TR.element, TR.token_id, '{$this->name}' , 0
FROM <report> TR
JOIN <report_attributes> TA
    ON TA.id = TR.token_id
WHERE TR.module = 'Functions_Php' AND
      TA.Classes_MethodsCall = 'No'
SQL;
	    $res = $this->execQueryInsert('report',$query);

	    $query = <<<SQL
SELECT DISTINCT element 
FROM <report> 
WHERE module = '{$this->name}'
SQL;
	    $res = $this->execQuery($query);

        $functions = array();
        while($row = $res->fetchColumn()) {
            $functions[] = strtolower($row);
        }
        
        $exts = Cornac_Auditeur_Analyzer::getPHPExtensions(); 
        foreach($exts as $ext) {
            $ext = strtolower($ext);
            $phpfunctions = Cornac_Auditeur_Analyzer::getPHPFunctions($ext);
            if (!is_array($phpfunctions)) { 
                continue; 
            }
            if (empty($phpfunctions)) {
                continue; 
            }
            $list = array_intersect($phpfunctions, $functions);
            if (count($list) > 0) {
                $in = join("','", $list);
                $query = <<<SQL
UPDATE <report> 
    SET element = '$ext' 
WHERE module = '{$this->name}' AND 
      element in ( '$in')
    
SQL;
                $res = $this->execQuery($query);
                $functions = array_diff($functions, $list);
            }
            unset($list);
        }

        $phpfunctions = Cornac_Auditeur_Analyzer::getPHPStandardFunctions();
        $list = array_intersect($phpfunctions, $functions);
        if (count($list) > 0) {
            $in = "'".join("','", $list)."'";
            $query = <<<SQL
UPDATE <report> 
    SET element = 'standard' 
WHERE module = '{$this->name}' AND 
      element in ( $in)
SQL;
            $res = $this->execQuery($query);
            $functions = array_diff($functions, $list);
        }

// @todo move this to a temporary table 

	    // @section : searching via classes usage
	    $query = <<<SQL
SELECT NULL, file, element, token_id, '{$this->name}_tmp', 0
FROM <report> 
WHERE module = 'Classes_Php'
SQL;
	    $res = $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT DISTINCT element 
    FROM <report> 
WHERE module = '{$this->name}_tmp'
SQL;
	    $res = $this->execQuery($query);

        $classes = array();
        while($row = $res->fetchColumn()) {
            $classes[] = strtolower($row);
        }
        
        $exts = Cornac_Auditeur_Analyzer::getPHPExtClasses(); 

        foreach($exts as $ext => $ext_classes) {
            if (!is_array($classes)) { 
                continue; 
            }
            if (empty($classes)) {
                continue; 
            }
            if (!isset($ext_classes['classes'])) {  
                // @note there is a problem with the dictionary, with this $ext
                continue; 
            }
            $list = array_intersect($classes, $ext_classes['classes']);
            if (count($list) > 0) {
                $in = "'".join("', '", $list)."'";
        	    $query = <<<SQL
UPDATE <report> SET element = '$ext',
                    module='{$this->name}'
WHERE module = '{$this->name}_tmp' AND 
      element IN ( $in )
SQL;
        	    $res = $this->execQuery($query);
            }

            $classes = array_diff($classes, $list);
            unset($list);
        }

        if (count($classes) > 0) {
            $query = <<<SQL
DELETE FROM <report> 
    WHERE module = '{$this->name}_tmp'
SQL;
   	        $res = $this->execQuery($query);
   	    }
        return true;
	}
}

?>