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

class Cornac_Auditeur_Analyzer_Classes_Unused extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused classes';
	protected	$description = 'Unused classes : they are defined in the code, and never instantiated. They may, if a variable new was, though.';

	function dependsOn() {
	    return array('Classes_Definitions','Classes_News','Classes_Hierarchy');
	}
	
	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, TR1.file, TR1.element AS code, TR1.id, '{$this->name}', 0
FROM <report>  TR1
LEFT JOIN <report>  TR2 
    ON TR1.element = TR2.element AND 
       TR2.module='Classes_News' 
WHERE TR1.module = 'Classes_Definitions' AND 
      TR2.module IS NULL
SQL;
        $this->execQueryInsert('report', $query);

// @note we need to check extensions : we have them in the dot report, from Classes_Hierarchy
        $query = <<<SQL
SELECT TRD.a
FROM <report>  TR1
JOIN <report_dot> TRD
    ON TRD.b = TR1.element
WHERE TR1.module = 'Classes_News' AND 
      TRD.module = 'Classes_Hierarchy'
SQL;
        $res = $this->execQuery($query);
        $extensions = $res->fetchAll(PDO::FETCH_COLUMN,0);
        $in = join("', '", $extensions);


        $query = <<<SQL
DELETE FROM <report> 
WHERE module='{$this->name}' AND element IN ('$in')
SQL;
        $res = $this->execQuery($query);

// @note same as above, but with 2 levels for extensions
        $query = <<<SQL
SELECT TRD2.a
FROM <report>  TR1
JOIN <report_dot> TRD1
    ON TRD1.b = TR1.element
JOIN <report_dot> TRD2
    ON TRD2.b = TRD1.a
WHERE TR1.module = 'Classes_News' AND 
      TRD1.module = 'Classes_Hierarchy' AND
      TRD2.module = 'Classes_Hierarchy'
SQL;

        $res = $this->execQuery($query);
        $extensions = $res->fetchAll(PDO::FETCH_COLUMN,0);
        $in = join("', '", $extensions);


        $query = <<<SQL
DELETE FROM <report> 
WHERE module='{$this->name}' AND element IN ('$in')
SQL;
        $res = $this->execQuery($query);

// @note same as above, but with 3 levels for extensions
        $query = <<<SQL
SELECT TRD3.a
FROM <report>  TR1
JOIN <report_dot> TRD1
    ON TRD1.b = TR1.element
JOIN <report_dot> TRD2
    ON TRD2.b = TRD1.a
JOIN <report_dot> TRD3
    ON TRD3.b = TRD2.a
WHERE TR1.module = 'Classes_News' AND 
      TRD1.module = 'Classes_Hierarchy' AND
      TRD2.module = 'Classes_Hierarchy' AND 
      TRD3.module = 'Classes_Hierarchy'          
SQL;

        $res = $this->execQuery($query);
        $extensions = $res->fetchAll(PDO::FETCH_COLUMN,0);
        $in = join("', '", $extensions);


        $query = <<<SQL
DELETE FROM <report> 
WHERE module='{$this->name}' AND
      element IN ('$in')
SQL;
        $res = $this->execQuery($query);

// @note same as above, but with 4 levels for extensions
        $query = <<<SQL
SELECT TRD4.a
FROM <report>  TR1
JOIN <report_dot> TRD1
    ON TRD1.b = TR1.element
JOIN <report_dot> TRD2
    ON TRD2.b = TRD1.a
JOIN <report_dot> TRD3
    ON TRD3.b = TRD2.a
JOIN <report_dot> TRD4
    ON TRD4.b = TRD3.a
WHERE TR1.module = 'Classes_News' AND 
      TRD1.module = 'Classes_Hierarchy' AND
      TRD2.module = 'Classes_Hierarchy' AND 
      TRD3.module = 'Classes_Hierarchy' AND 
      TRD4.module = 'Classes_Hierarchy'          
SQL;
        $res = $this->execQuery($query);
        $extensions = $res->fetchAll(PDO::FETCH_COLUMN,0);
        $in = join("', '", $extensions);


        $query = <<<SQL
DELETE FROM <report> 
WHERE module='{$this->name}' AND 
      element IN ('$in')
SQL;
        $res = $this->execQuery($query);

// @attention may we need some more queries, with more joins, or a clever while loop. Up to now, it's sufficient

        return true;
	}
}

?>