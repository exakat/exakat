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

class Cornac_Auditeur_Analyzer_Functions_ArglistCalled extends Cornac_Auditeur_Analyzer {
    protected    $title = 'Used arguments';
    protected    $description = 'List the number of arguments being used in functioncall. For example, f(1,2,3) => f(3 args), f() => f(0 args). ';

    function __construct($mid) {
        parent::__construct($mid);
    }

    public function analyse() {
        $this->cleanReport();

        $query = <<<SQL
SELECT NULL, T1.file, CONCAT(T2.code,'(', count(*),' args)') AS code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT1
    ON T1.id = TT1.token_id AND 
       TT1.type = 'function'
JOIN <tokens> T2
    ON T2.file = T1.file AND 
       TT1.token_sub_id = T2.id
JOIN <tokens_tags> TT2
    ON T1.id = TT2.token_id AND 
       TT2.type='args'
JOIN <tokens> T3
    ON T3.file = T1.file AND TT2.token_sub_id = T3.id AND T3.type = 'arglist'
JOIN <tokens> T4
    ON T4.file = T1.file AND 
       T4.left BETWEEN T3.left AND T3.right AND 
       T4.level = T3.level + 1
WHERE T1.type = 'functioncall'
GROUP BY T1.id;
SQL;
        $this->execQueryInsert('report', $query);

        return true;
    }
}

?>