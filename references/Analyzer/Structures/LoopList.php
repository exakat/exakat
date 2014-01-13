<?php 

class Cornac_Auditeur_Analyzer_Structures_LoopList extends Cornac_Auditeur_Analyzer {
	protected	$title = 'while(list())';
	protected	$description = 'Spot structures such as while(list($a, $b) = each($c)) {} that are really slow. ';

	public function analyse() {
        $this->cleanReport();

// @todo of course, update this useless query. :)
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.left BETWEEN T1.left AND T1.right AND
       T2.type = 'functioncall' AND
       T2.code = 'list'
JOIN <cache> TC
    ON T2.id = TC.id
WHERE T1.type = '_while'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>