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
}

?>