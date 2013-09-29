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

class Cornac_Auditeur_Analyzer_Functions_WithoutReturns extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Functions without return';
	protected	$description = 'Functions/methods which have no return. They only return null, all the time.';

	
	public function analyse() {
        $this->cleanReport();

// @note for methods
        $query = <<<SQL
SELECT NULL, T1.file, CONCAT(T1.class,'::', T1.scope), T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.class != '' AND
      T1.scope!='global' AND 
      T1.scope NOT IN ('__construct','__destruct')
GROUP BY file, class, scope 
HAVING SUM(if(type='_return', 1, 0)) = 0
SQL;
        $this->execQueryInsert('report', $query);

// @note for functions
        $query = <<<SQL
SELECT NULL, T1.file, T1.scope, T1.id, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.class = '' AND 
     T1.scope != 'global'
GROUP BY file, class, scope 
HAVING SUM(if(type='_return' OR code IN ('die','exit'), 1, 0)) = 0
SQL;
        $this->execQueryInsert('report', $query);
        
        return true; 
	}
}

?>