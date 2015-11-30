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
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70', 'CompatibilityPHP71',
                              'Appinfo', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

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
        
        $sqlite = new \Sqlite3($sqliteFile);
        $sqlite->query('CREATE TABLE results (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                fullcode STRING,
                                                file STRING,
                                                line INTEGER,
                                                namespace STRING,
                                                class STRING,
                                                function STRING,
                                                analyzer STRING
                                              )');

        $sqlite->query('CREATE TABLE resultsCounts (   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                       analyzer STRING,
                                                       count INTEGER)');
        display('Inited tables');
        
        foreach($this->themes as $thema) {
            display('Processing thema "'.$thema.'"');
            $themaClasses = \Analyzer\Analyzer::getThemeAnalyzers($thema);

            $sqlQuery = <<<SQL
INSERT INTO results (
        "id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer"
        ) 
    VALUES (
            NULL, :fullcode, :file, :line, :namespace, :class, :function, :analyzer
            )
SQL;
            $stmt = $sqlite->prepare($sqlQuery);

            $counts = array();
            foreach($this->datastore->getRow('analyzed') as $row) {
                $counts[$row['analyzer']] = $row['counts'];
            }

            foreach($themaClasses as $class) {
//                display('     Processing class "'.$class.'"');
                $count = (int) $this->datastore->getHash($class);

                $sqlQuery = 'INSERT INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, "'.$class.'", '.$count.' )';
                $sqlite->query($sqlQuery);

                $stmt->bindValue(':class', $class, SQLITE3_TEXT);

                if (!isset($counts[$class])) {
                    // May be it as out of configuration or incompatible with the current run. Ignore but don't display it.
                    continue;
                }
                
                if ($counts[$class] > 0) {
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
                        die( "Couldn't run the query and get a result : \n" .
                             "Query : " . $query . " \n".
                             print_r($res, true));
                    }

                    $res = $res->results;
                    
                    foreach($res as $result) {
                        if (!is_object($result)) {
                            $this->log->log("Object expected but not found\n".print_r($result)."\n");
                            continue;
                        }
                        
                        if (!isset($result->class)) {
                            print_r($result);
                            print "Analyzer : $class\n";
                            die();
                        }
                        
                        $stmt->bindValue(':fullcode', $result->fullcode,      SQLITE3_TEXT);
                        $stmt->bindValue(':file',     $result->file,          SQLITE3_TEXT);
                        $stmt->bindValue(':line',     $result->line,          SQLITE3_TEXT);
                        $stmt->bindValue(':namespace',$result->{'namespace'}, SQLITE3_TEXT);
                        $stmt->bindValue(':class',    $result->class,         SQLITE3_TEXT);
                        $stmt->bindValue(':function', $result->function,      SQLITE3_TEXT);
                        $stmt->bindValue(':analyzer', $class,                 SQLITE3_TEXT);
                        
                        $result = $stmt->execute();
                    }
                }
            }
        }
    }
}

?>
