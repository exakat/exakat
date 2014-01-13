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

class Cornac_Auditeur_Analyzer_Literals_Long extends Cornac_Auditeur_Analyzer { 
	protected	$title = 'Long literaux';
	protected	$description = 'Really long literals : over 1ko';


	function dependsOn() {
	    return array('Literals_Definitions');
	}

	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, TR1.file, TRIM(code), TR1.id, '{$this->name}', 0
FROM <tokens> TR1
WHERE type = 'literals' AND
      LENGTH(code) > 1024
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>