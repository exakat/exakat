<?php

namespace Report\Content;

class Compilations extends \Report\Content {
    private $info = array();

    private $project = null;
    private $neo4j = null;
    private $mysql = null;
    
    public function collect() {
        $versions = array('5.3' => '53', '5.4' => '54', '5.5' => '55', '5.6' => '56');

        $queryTemplate = "g.V.has('atom', 'File').count()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();
        $total = $vertices[0][0];
        
        foreach($versions as $version => $suffix) {
            $files = \Analyzer\Analyzer::$datastore->getCol('compilation'.$suffix, 'file');
            if (empty($files)) {
                $files = "No compilation error found.";
                $errors = "N/A";
                $total_error = 'None';
            } else {
                $errors = array_count_values(\Analyzer\Analyzer::$datastore->getCol('compilation'.$suffix, 'error'));
                $errors = array_keys($errors);
                $total_error = count($files).' (' .number_format(count($files) / $total * 100, 0). '%)';
            }

            $info = array('version'       => $version,
                          'total'         => $total,
                          'total_error'   => $total_error,
                          'files'         => $files,
                          'errors'        => $errors,
                          );

            $this->info[] = $info;
        }
        
        return true;
    }
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function setMysql($client) {
        $this->mysql = $client;
    }

    public function setProject($project) {
        $this->project = $project;
    }

    public function getInfo() {
        return $this->info;
    }
}

?>