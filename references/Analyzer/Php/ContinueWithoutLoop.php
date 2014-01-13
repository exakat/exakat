<?php 

class Cornac_Auditeur_Analyzer_Php_ContinueWithoutLoop extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Continue Without Loop';
	protected	$description = 'Spot continue and break called outside a loop. PHP 5.3 forbid this';

	public function analyse() {
        $this->cleanReport();

// @todo spot continue/break within one or several loops
	    $query = <<<SQL
SELECT NULL, file, code, id,'{$this->name}', 0 FROM (
SELECT NULL, T1.file, TC.code, T1.id, T3.code AS deep, COUNT(*) AS COUNT 
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND
       T1.left BETWEEN T2.left AND T2.right AND
       T2.type IN ('_foreach','_for','_while','_dowhile','_switch')
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.type IN ('_continue_','_break_') AND
       T3.left = T1.left + 1
JOIN <cache> TC
    ON TC.id = T1.id
WHERE T1.type IN ('_continue', '_break') AND
      T3.code > 0
GROUP BY T1.id ) MAIN 
WHERE COUNT < deep
SQL;
        $this->execQueryInsert('report', $query);

// @todo spot continue/break without loop
	    $query = <<<SQL
SELECT NULL, T1.file, TC.code, T1.id, '{$this->name}', 0
FROM <tokens> T1
LEFT JOIN <tokens> T2
    ON T2.file = T1.file AND
       T1.left BETWEEN T2.left AND T2.right AND
       T2.type IN ('_foreach','_for','_while','_dowhile','_switch')
JOIN <tokens> T3
    ON T3.file = T1.file AND
       T3.type IN ('_continue_','_break_') AND
       T3.left = T1.left + 1
JOIN <cache> TC
    ON TC.id = T1.id
WHERE T1.type IN ('_continue', '_break') AND
      T2.code IS NULL
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>