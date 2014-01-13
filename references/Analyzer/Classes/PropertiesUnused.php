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

class Cornac_Auditeur_Analyzer_Classes_PropertiesUnused extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused properties';
	protected	$description = 'List unused properties : they are defined in a class, but never used in the code.';

    function __construct($mid) {
        parent::__construct($mid);
    }

	public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, T1.file, T1.code AS code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
WHERE scope='global'   AND 
      type ='variable' AND  
      class != '' AND  
      code NOT IN (  
         SELECT CONCAT('$', S2.code)
           FROM <tokens> S1
           JOIN <tokens> S2
             ON S2.file = S1.file AND 
                S2.left BETWEEN S1.left AND S1.right
           WHERE S1.class  = T1.class AND 
                 S1.scope != 'global'  AND 
                 S1.type   = 'property' AND 
                 S2.type='literals' 
                  )
SQL;
        $this->execQueryInsert('report', $query);
	}
}

?>