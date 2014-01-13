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

class Cornac_Auditeur_Analyzer_Quality_DangerousCombinaisons extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Dangerous matchings';
	protected	$description = 'List of file that are combining several dangerous values or structures (ex. $_POST and shell_exec). Probably worth checking.';


	public function analyse() {
        $this->cleanReport();
        
        // @todo move this to Module
        $combinaisons = parse_ini_file('dict/combinaisons.ini', true);

        foreach ($combinaisons as $nom => $combinaison) {
            $in = "'".join("','", $combinaison['combinaison'])."'";
            $count = count($combinaison['combinaison']);
            // @todo : this shouldn't be sufficient. One must work on distinct occurences... may be a sub query will do

// @note : some token duplicate code from other tokens (like functioncall, which have no code by itself, but get a copy of their name for easy reference)
// @note so, we need to ignore some types. 
            $query = <<<SQL
SELECT NULL, T1.file, '$nom', T1.code, '{$this->name}', 0
FROM <tokens> T1
WHERE T1.type NOT IN ('functioncall','method')
GROUP BY file
HAVING SUM(IF (code IN ($in), 1, 0)) >= $count
SQL;
            $this->execQueryInsert('report', $query);
        }
        return true;
	}
}

?>