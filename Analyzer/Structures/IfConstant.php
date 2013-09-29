<?php 

class Cornac_Auditeur_Analyzer_Structures_IfConstant extends Cornac_Auditeur_Analyzer {
	protected	$title = 'If then structures that are constant';
	protected	$description = 'Spot ifthen that are constants, like if(1) {}. ';

/*
// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}
*/
	public function analyse() {
        $this->cleanReport();

	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
JOIN <tokens_tags> TT
    ON TT.token_id = T1.id AND
       TT.type = 'condition'
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T2.id = TT.token_sub_id AND
       T2.right - T2.left = 3
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T2.left + 1 = T3.left AND
       T3.code IN ('true','false',1,0)
JOIN <tokens_cache> TC
    ON T2.id = TC.id
WHERE T1.type = 'ifthen'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>