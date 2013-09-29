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


class Cornac_Auditeur_Analyzer_Quality_ExternalLibraries extends Cornac_Auditeur_Analyzer {
    protected    $title = 'Title for Quality_ExternalStructures';
    protected    $description = 'This is the special analyzer Quality_ExternalStructures (default doc).';

    function __construct($mid) {
        parent::__construct($mid);
    }

// @doc if this analyzer is based on previous result, use this to make sure the results are here
    function dependsOn() {
        return array('Quality_ExternalStructures');
    }

    public function analyse() {
        $this->cleanReport();

        $list = Cornac_Auditeur_Analyzer::getPopLib();
        
        foreach($list as $ext => $characteristics) {
        // @doc in case there are no classes defined. 
            if (!isset($characteristics['classes'])) { continue; }
            $in = "'".join("', '", $characteristics['classes'])."'";

            // @doc search for usage as class extensions
            $query = <<<SQL
SELECT NULL, T1.file, '$ext', T1.id, '{$this->name}', 0
FROM <report> T1
WHERE T1.module = 'Quality_ExternalStructures' AND
      T1.element IN ($in)
GROUP BY '$ext'
SQL;
            $this->execQueryInsert('report', $query);
        }

        return true;
    }
}

?>