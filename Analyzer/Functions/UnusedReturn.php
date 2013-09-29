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


class Cornac_Auditeur_Analyzer_Functions_UnusedReturn extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused return values';
	protected	$description = 'Spot function whose return values are not used. Function is called, but result is just ignored. Currently works on PHP functions. ';
	protected   $tags = array('quality');


	function dependsOn() {
	    return array();
	}

	public function analyse() {
        $this->cleanReport();

        $no_return = array('echo','print','define','var_dump','print_r','unset',
                           'die','exit');
        $in = "'".join("', '", $no_return)."'";
        
// @todo update list of authorizes functions (they're in the first IN)
// @todo check on other application the list of authorized structures (second IN)
// @todo make another level for noscream, arobases, etc. : or find a way to ignore them
// @todo check immediatly if return value is used in a foreach 
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->notCode($no_return)
                      ->getParent(1)
                      ->notType(array('noscream','_array','comparison','logical','clevaleur','ternaryop','not','concatenation','method_static','parenthesis','method','affectation','arglist'));
        $this->backend->run();

/*
	    $query = <<<SQL
SELECT NULL, T1.file, T1.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2 
    ON T2.file = T1.file AND
       T1.left BETWEEN T2.left AND T2.right AND
       T2.level = T1.level - 1
WHERE T1.type = 'functioncall' AND
      T1.code NOT IN ($in) AND 
      T2.type NOT IN ('noscream','_array','comparison','logical','clevaleur','ternaryop','not','concatenation','method_static','parenthesis','method','affectation','arglist')
SQL;
        $this->execQueryInsert('report', $query);
*/
        return true;
	}
}

?>