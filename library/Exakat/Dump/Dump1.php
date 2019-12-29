<?php

namespace Exakat\Dump;

use Sqlite3;
use Exakat\Reports\Helpers\Results;


class Dump1 extends Dump {
    public function fetchAnalysers(array $analysers) : Results {
        $query = 'SELECT fullcode, file, line, analyzer FROM results WHERE analyzer IN (' . makeList($analysers) . ')';
        $res = $this->sqlite->query($query);

        return new Results($res, array('phpsyntax' => array('fullcode' => 'htmlcode')));
    }

    public function fetchAnalysersCounts(array $analysers) : Results {
        $query = 'SELECT analyzer, count FROM resultsCounts WHERE analyzer IN (' . makeList($analysers) . ')';
        $res = $this->sqlite->query($query);

        return new Results($res);
    }

    public function fetchTable(string $table, array $cols = array()) : Results {
        if (empty($cols)) {
            $cols = '*';
        } else {
            $list = array();
            foreach($cols as $k => $col) {
                if (is_int($k)) {
                    $list[] = $col;
                } else {
                    $list[] = "$col as $k";
                }
            }
            $cols = implode(', ', $cols);
        }

        $query = "SELECT $cols FROM $table";
        $res = $this->sqlite->query($query);

        return new Results($res);
    }

    public function getExtensionList() : Results {
        $query = <<<'SQL'
SELECT analyzer, count(*) AS count FROM results 
    WHERE analyzer LIKE "Extensions/Ext%"
    GROUP BY analyzer
    ORDER BY count(*) DESC
SQL;

        return $this->query($query);
    }

    public function fetchHashResults(string $key) : Results {
        $query = <<<SQL
SELECT key, value FROM hashResults
WHERE name = "$key"
ORDER BY key + 0
SQL;

        return $this->query($query);
    }

    public function getCit($type = 'class') {
        assert(in_array($type, array('class', 'trait', 'interface')));

        $query = "SELECT name FROM cit WHERE type='$type' ORDER BY name";

        return $this->query($query);
    }

    private function query(string $query) : Results {
        $res = $this->sqlite->query($query);

        return new Results($res);
    }
    
    public function fetchTableFunctions() : Results {
        $res = $this->sqlite->query(<<<'SQL'
SELECT functions.*, 
GROUP_CONCAT((CASE arguments.typehint WHEN ' ' THEN '' ELSE arguments.typehint || ' ' END ) || 
              CASE arguments.reference WHEN 0 THEN '' ELSE '&' END || 
              CASE arguments.variadic WHEN 0 THEN '' ELSE '...' END  || arguments.name || 
              (CASE arguments.init WHEN ' ' THEN '' ELSE ' = ' || arguments.init END),
             ', ' ) AS signature

FROM functions

LEFT JOIN arguments
    ON functions.id = arguments.methodId AND
       arguments.citId = 0
GROUP BY functions.id

SQL
        );

        return new Results($res);
    }

    public function fetchTableMethods() : Results {
        $res = $this->sqlite->query(<<<'SQL'
SELECT methods.*, 
GROUP_CONCAT((CASE arguments.typehint WHEN ' ' THEN '' ELSE arguments.typehint || ' ' END ) || 
              CASE arguments.reference WHEN 0 THEN '' ELSE '&' END || 
              CASE arguments.variadic WHEN 0 THEN '' ELSE '...' END  || arguments.name || 
              (CASE arguments.init WHEN ' ' THEN '' ELSE ' = ' || arguments.init END),
             ', ' ) AS signature,
cit.type AS cit
FROM methods
LEFT JOIN arguments
    ON methods.id = arguments.methodId
JOIN cit
    ON methods.citId = cit.id
GROUP BY methods.id
SQL
        );


        return new Results($res);
    }
    
    public function fetchTableCit() : Results {
        $res = $this->sqlite->query(<<<SQL
SELECT cit.*, 
       cit.type AS type, 
       namespace,

       ( SELECT GROUP_CONCAT(CASE WHEN cit5.id IS NULL THEN traits.implements ELSE cit5.name END, ',') 
       
       FROM cit_implements AS traits
LEFT JOIN cit cit5
    ON traits.implements = cit5.id
    WHERE traits.implementing = cit.id AND
       traits.type = 'use') AS use,

       (SELECT GROUP_CONCAT(CASE WHEN cit4.id IS NULL THEN implements.implements ELSE cit4.name END, ',') FROM cit_implements AS implements
LEFT JOIN cit cit4
    ON implements.implements = cit4.id
    WHERE implements.implementing = cit.id AND
       implements.type = 'implements') AS implements,

        CASE WHEN cit2.extends IS NULL THEN cit.extends ELSE cit2.name END AS extends 
        
        FROM cit

LEFT JOIN cit cit2 
    ON cit.extends = cit2.id

LEFT JOIN cit_implements AS interfaces
    ON interfaces.implementing = cit.id AND
       interfaces.type = 'implements'
LEFT JOIN cit cit4
    ON interfaces.implements = cit4.id
LEFT JOIN namespaces
    ON namespaces.id = cit.namespaceId


GROUP BY cit.id
SQL
        );

        return new Results($res);
    }
    
    public function fetchTablePhpcity() : Results {
        $query = <<<'SQL'
SELECT
     cit.id,
     files.file AS file,
     namespaces.namespace AS namespace,
     name,
     extends,
     (SELECT GROUP_CONCAT(implements) FROM cit_implements WHERE cit_implements.implementing = cit.id) AS implements,
     end - begin AS no_lines,
     (SELECT COUNT(*) FROM properties WHERE properties.citId = cit.id) AS no_attrs,
     (SELECT COUNT(*) FROM methods WHERE methods.citId = cit.id) AS no_methods,
     CASE type 
           WHEN 'trait' 
               THEN 1 
           ELSE 0 END AS trait,
     abstract,
     final,
     'class' AS type
        
     FROM cit
     JOIN namespaces
        ON namespaces.id = cit.namespaceId
     JOIN files
       ON cit.file = files.id
SQL;
        $res = $this->sqlite->query($query);

        return new Results($res);
    }

    public function fetchTableUml() : Results {
        $query = <<<'SQL'
SELECT name, cit.id, extends, type, namespace, 
       (SELECT GROUP_CONCAT(method,   "||")   FROM methods    WHERE citId = cit.id) AS methods,
       (SELECT GROUP_CONCAT( case when value != '' then property || " = " || substr(value, 0, 40) else property end, "||") FROM properties WHERE citId = cit.id) AS properties
    FROM cit
    JOIN namespaces
        ON namespaces.id = cit.namespaceId
SQL;
        $res = $this->sqlite->query($query);

        return new Results($res);
    }
    
    public function getAnalyzedFiles(array $list) : int {
        $list = makeList($list);

        $query = "SELECT COUNT(DISTINCT file) FROM results WHERE file LIKE '/%' AND analyzer IN ($list)";
        $result = $this->sqlite->querySingle($query);

        return $result;
    }

    public function getTotalAnalyzer() : array {
        $query = <<<SQL
SELECT COUNT(*) AS total, 
       COUNT(CASE WHEN rc.count != 0 THEN 1 ELSE null END) AS yielding 
    FROM resultsCounts AS rc
    WHERE rc.count >= 0
SQL;
        $result = $this->sqlite->query($query);

        return $result->fetchArray(\SQLITE3_ASSOC);
    }

}

?>