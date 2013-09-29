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

class Cornac_Auditeur_Analyzer_Quality_Mvc extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Type files as M-V-C';
	protected	$description = 'Try to guess if the file is Model (only database access), Controller (inclusions, validation...), Vue (exit, views...)';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}
	
	public function analyse() {
        $this->cleanReport();

// @doc inclusions are for controlers
	    $query = <<<SQL
SELECT DISTINCT NULL, T1.file, 'controler', 1, '{$this->name}', 0
FROM <tokens> T1
WHERE code IN ('include','require','include_once','require_once')
SQL;
        $this->execQueryInsert('report', $query);

// @doc echo are for template
	    $query = <<<SQL
SELECT DISTINCT NULL, T1.file, 'template', 1, '{$this->name}', 0
FROM <tokens> T1
WHERE code IN ('echo','print','phpinfo')
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
SELECT DISTINCT NULL, T1.file, 'template', 1, '{$this->name}', 0
FROM <tokens> T1
WHERE type IN ('rawtext')
SQL;
        $this->execQueryInsert('report', $query);

// @doc the remaining files are unknown type (no M, V or C) : time to update the analyzer
	    $query = <<<SQL
CREATE TEMPORARY TABLE Quality_Mvc
SELECT DISTINCT file FROM <tokens>
SQL;
        $this->execQuery($query);

// @doc the rest is undecided
	    $query = <<<SQL
SELECT NULL, Quality_Mvc.file, 'undecided', 0, '{$this->name}', 0
FROM Quality_Mvc
LEFT JOIN <report> TR
    ON Quality_Mvc.file = TR.file AND
       module='mvc'  
WHERE TR.file IS NULL
SQL;
        $this->execQueryInsert('report', $query);

	    $query = <<<SQL
DROP TABLE Quality_Mvc
SQL;
        $this->execQuery($query);

        return true;
	}
}

?>