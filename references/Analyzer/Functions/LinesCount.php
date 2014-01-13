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

class Cornac_Auditeur_Analyzer_Functions_LinesCount extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Lines of code';
	protected	$description = 'Line of code, function by function';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}
	
	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, CONCAT( (T2.line - T1.line)), T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T1.file = T2.file AND
       T2.right = T1.right - 2
LEFT JOIN <tokens> T3
    ON T1.file = T3.file AND
       T3.left BETWEEN T1.left AND T1.right AND
       T3.type = 'literals' AND 
       T3.code = 'abstract'
WHERE T1.type='_function' 
SQL;
        $this->execQueryInsert('report', $query);

/*
select min(line)
from functions 

select max(line)
from functions



*/

        return true;
	}
}

?>