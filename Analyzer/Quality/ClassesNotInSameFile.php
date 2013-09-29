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


class Cornac_Auditeur_Analyzer_Quality_ClassesNotInSameFile extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Classes not in same name file';
	protected	$description = 'Spot classes that are not in an eponymous file (aka, class X in file X.php), nor using _ as separator (aka X_Y stored in X/Y.php).';


	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, T2.code, T1.id, '{$this->name}', 0
FROM <tokens> T1 
JOIN <tokens_tags> TT     
    ON TT.token_id = T1.id AND        
    TT.type = 'name' 
JOIN <tokens> T2     
    ON T2.id = TT.token_sub_id AND        
       T1.file = T2.file 
WHERE T1.type='_class' AND
      LOCATE(replace(T2.code,'_','/'), T1.file) = 0 AND
      LOCATE(T2.code, T1.file) = 0
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>