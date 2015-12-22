<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Tasks;

class Dump extends Tasks {
    // Beware : shared with Project
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56',
                              'CompatibilityPHP70', 'CompatibilityPHP71',
                              'Appinfo', 'Appcontent', '"Dead code"', 'Security', 'Custom',
                              'Analyze');
    private $stmtResults = null;
    private $stmtResultsCount = null;
    
    const WAITING_LOOP = 200;
    
    public function run(\Config $config) {
        if (!file_exists($config->projects_root.'/projects/'.$config->project)) {
            display('No such project as "'.$config->project.'"');
            die();
        }
        
        $sqliteFile = $config->projects_root.'/projects/'.$config->project.'/dump.sqlite';
        if (file_exists($sqliteFile)) {
            display('Removing old dump.sqlite');
            unlink($sqliteFile);
        }
        
        \Analyzer\Analyzer::initDocs();
        
        $sqlite = new \Sqlite3($sqliteFile);
        $this->getAtomCounts($sqlite);

        $sqlite->query('CREATE TABLE results (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                fullcode STRING,
                                                file STRING,
                                                line INTEGER,
                                                namespace STRING,
                                                class STRING,
                                                function STRING,
                                                analyzer STRING,
                                                severity STRING
                                              )');

        $sqlite->query('CREATE TABLE resultsCounts (   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                       analyzer STRING,
                                                       count INTEGER)');
        display('Inited tables');

        $sqlQuery = <<<SQL
INSERT INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES ( NULL, :fullcode, :file,  :line,  :namespace,  :class,  :function,  :analyzer,  :severity )
SQL;
        $this->stmtResults = $sqlite->prepare($sqlQuery);

        $sqlQuery = <<<SQL
INSERT INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, :class, :count )
SQL;
        $this->stmtResultsCounts = $sqlite->prepare($sqlQuery);

        $themes = array();
        foreach($this->themes as $thema) {
            display('Processing thema "'.$thema.'"');
            $themaClasses = \Analyzer\Analyzer::getThemeAnalyzers($thema);

            $themes[] = $themaClasses;
        }
        $themes = array_merge(...$themes);
        $themes = array_keys(array_count_values($themes));

        $rounds = 0;
        $sqlitePath = $config->projects_root.'/projects/'.$config->project.'/datastore.sqlite';
        while (count($themes) > 0) {
            ++$rounds;
            $this->log->log( "Run round $rounds");

            $counts = array();
            $datastore = new \Sqlite3($config->projects_root.'/projects/'.$config->project.'/datastore.sqlite', \SQLITE3_OPEN_READONLY);
            $datastore->busyTimeout(5000);
            $res = $datastore->query('SELECT * FROM analyzed');
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $counts[$row['analyzer']] = $row['counts'];
            }
            $datastore->close();
            unset($datastore);
        
            foreach($themes as $id => $thema) {
                if (isset($counts[$thema])) {
                    display( $thema." : ".($counts[$thema] >= 0 ? 'Yes' : 'N/A')."\n");
                    $this->processResults($thema, $counts[$thema]);
                    unset($themes[$id]);
                } else {
                    display( $thema." : No\n");
                }
            }

            $this->log->log( "Still ".count($themes)." to be processed\n");
            display("Still ".count($themes)." to be processed\n");
            if (count($themes) === 0) {
                $this->finish();
                return ;
            }
            $wait = rand(2,7);
            sleep($wait);
            display('Sleep '.$wait.' seconds');
            
            if ($rounds >= self::WAITING_LOOP) {
                $this->log->log( "Waited for ".self::WAITING_LOOP." loop. Now aborting. Aborting\n");
                $this->finish();
                return true;
            }
        }

        $this->finish();
        return ;
    }
        
    private function processResults($class, $count) {
        $this->stmtResultsCounts->bindValue(':class', $class, SQLITE3_TEXT);
        $this->stmtResultsCounts->bindValue(':count', $count, SQLITE3_INTEGER);

        $result = $this->stmtResultsCounts->execute();
        
        // No need to go further
        if ($count <= 0) {
            return;
        }

        $this->stmtResults->bindValue(':class', $class, SQLITE3_TEXT);
        $analyzerName = 'Analyzer\\\\'.str_replace('/', '\\\\', $class);
        
        $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzerName']].out
.sideEffect{
    // file
    rnamespace = 'Global';
    rclass = 'Global';
    rfunction = 'Global';
    
    if (it.token == 'T_FILENAME') {
        m = ['fullcode':it.fullcode, 'file':it.fullcode, 'line':0, 'namespace':'', 'class':'', 'function':'' ];
    } else {
        // Support for closure, traits or interfaces? 
        it.in.loop(1){true}{it.object.token in ['T_FILENAME', 'T_NAMESPACE', 'T_CLASS', 'T_TRAIT', 'T_INTERFACE', 'T_FUNCTION']}.each{
            if (it.token == 'T_FILENAME') {
                file = it.fullcode;
            } else if (it.token == 'T_NAMESPACE') {
                rnamespace = it.out('NAMESPACE').next().code;
            } else if (it.token in ['T_CLASS', 'T_INTERFACE', 'T_TRAIT']) {
                if (it.out('NAME').any()) {
                    // class, interface and trait all have names.
                    rclass = it.fullnspath;
                } else {
                    rfunction = 'Anonymous class';
                }
            } else if (it.token == 'T_FUNCTION') {
                if (it.out('NAME').any()) {
                    rfunction = it.out('NAME').next().code;
                } else {
                    rfunction = 'Closure';
                }
            }
        }
        m = ['fullcode':it.fullcode, 'file':file, 'line':it.line, 'namespace':rnamespace, 'class':rclass, 'function':rfunction ];
    }
}.transform{ m; }

GREMLIN;
        $res = gremlin_query($query);
        if (!isset($res->results)) {
            $this->log->log( "Couldn't run the query and get a result : \n" .
                 "Query : " . $query . " \n".
                 print_r($res, true));
            return ;
        }

        $res = $res->results;
        
        $saved = 0;
        $severity = \Analyzer\Analyzer::$docs->getSeverity(str_replace('\\\\', '\\', $analyzerName));

        foreach($res as $result) {
            if (!is_object($result)) {
                $this->log->log("Object expected but not found\n".print_r($result, true)."\n");
                continue;
            }
            
            if (!isset($result->class)) {
                continue;
            }
            
            $this->stmtResults->bindValue(':fullcode', $result->fullcode,      SQLITE3_TEXT);
            $this->stmtResults->bindValue(':file',     $result->file,          SQLITE3_TEXT);
            $this->stmtResults->bindValue(':line',     $result->line,          SQLITE3_TEXT);
            $this->stmtResults->bindValue(':namespace',$result->{'namespace'}, SQLITE3_TEXT);
            $this->stmtResults->bindValue(':class',    $result->class,         SQLITE3_TEXT);
            $this->stmtResults->bindValue(':function', $result->function,      SQLITE3_TEXT);
            $this->stmtResults->bindValue(':analyzer', $class,                 SQLITE3_TEXT);
            $this->stmtResults->bindValue(':severity', $severity,              SQLITE3_TEXT);
            
            $this->stmtResults->execute();
            ++$saved;
        }
        $this->log->log("$class : dumped $saved");
        
        if ($count != $saved) {
            display("$saved results saved, $count expected for $class\n");
        } else {
            display("All $saved results saved for $class\n");
        }
    }

    private function getAtomCounts($sqlite) {
        $sqlite->query('CREATE TABLE atomsCounts (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                    atom STRING,
                                                    count INTEGER
                                              )');

        $sqlQuery = <<<SQL
INSERT INTO atomsCounts ("id", "atom", "count") VALUES (NULL, :atom, :count )
SQL;
        $insert = $sqlite->prepare($sqlQuery);

        
        foreach(\Tokenizer\Token::$types as $class) {
            $tokenClass = "\\Tokenizer\\$class";
            if (!isset($tokenClass::$atom)) {
                // Some classes have no such property
                continue;
            }
            $atom = $tokenClass::$atom;
            $query = "g.idx('atoms')[['atom':'$atom']].count()";
            $res = gremlin_query($query);
            if (!is_object($res) || !isset($res->results)) {
                $this->log->log( "Couldn't run the query and get a result : \n" .
                     "Query : " . $query . " \n".
                     print_r($res, true));
                continue ;
            }

            $res = $res->results;
            $insert->bindValue(':atom', $atom ,   SQLITE3_TEXT);
            $insert->bindValue(':count', $res[0], SQLITE3_INTEGER);
            $insert->execute();
        }
    }
    
    private function finish() {
        $this->stmtResultsCounts->bindValue(':class', 'Project/Dump', SQLITE3_TEXT);
        $this->stmtResultsCounts->bindValue(':count', 1, SQLITE3_INTEGER);

        $result = $this->stmtResultsCounts->execute();
    }

}

?>
