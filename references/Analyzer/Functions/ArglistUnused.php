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

class Cornac_Auditeur_Analyzer_Functions_ArglistUnused extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Unused arguments';
	protected	$description = 'Function/method that request arguments, but never use them.';

	
	public function analyse() {
        $this->cleanReport();
        
        // @todo if block uses func_get_args and co, ignore this
        // @todo display class/method 

	    $query = <<<SQL
SELECT T1.id, T1.code, T1.file, TT.type, TT.token_sub_id , TC.code AS signature
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id 
JOIN <tokens_cache> TC
    ON T1.id = TC.id 
WHERE T1.type = '_function' AND 
      TT.type in ('args','block','abstract');
SQL;
        $res = $this->execQuery($query);
    
        $functions = array();
        while($row = $res->fetch()) {
            $functions[$row['id']][$row['type']] = $row['token_sub_id'];
            $functions[$row['id']]['function'] = $row['code'];
            $functions[$row['id']]['file'] = $row['file'];
            $functions[$row['id']]['signature'] = $row['signature'];
        }
        
        foreach($functions as $id => $infos) {
            extract($infos);
            if ($args == 0) { continue; }
    
            // @doc don't keep abstract properties
            if (isset($abstract)) { unset($abstract); continue; }
        
        	$query = <<<SQL
SELECT T2.code FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file and T2.left between T1.left and T1.right AND T2.type = 'variable'
WHERE T1.id = $args AND T2.code NOT IN (
    SELECT T2.code FROM <tokens> T1
    JOIN <tokens> T2
        ON T2.file = T1.file AND 
           T2.left BETWEEN T1.left AND T1.right AND
           T2.type = 'variable'
     WHERE T1.id = $block 
     )
SQL;

           $res = $this->execQuery($query);
           if ($res->rowCount() > 0) {
              $row = $res->fetch(PDO::FETCH_ASSOC);
              $vars = join(', ', $row);
        
              $query = <<<SQL
INSERT INTO <report> 
    VALUES ( 0, '$file', '$signature', $id, '{$this->name}', 0 )
SQL;
              $this->execQuery($query);
          }
       }
    }
}

?>