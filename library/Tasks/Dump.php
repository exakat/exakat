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
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                              'Appinfo', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

    public function run(\Config $config) {
        $datastore = new \Datastore($config);
        
        $sqliteFile = $config->projects_root.'/projects/'.$config->project.'/dump.sqlite';
        if (file_exists($sqliteFile)) {
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
        
        foreach($this->themes as $thema) {
            $themaClasses = \Analyzer\Analyzer::getThemeAnalyzers($thema);

            $sqlQuery = 'INSERT INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer") VALUES (
            NULL, :fullcode, :file, :line, :namespace, :class, :function, :analyzer)';
            $stmt = $sqlite->prepare($sqlQuery);

            foreach($themaClasses as $class) {
                $count = $datastore->getHash($class) + 0;

                $sqlQuery = 'INSERT INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, "'.$class.'", '.$count.' )';
                $sqlite->query($sqlQuery);

                $stmt->bindValue(':class', $class, SQLITE3_TEXT);

                if ($count > 0) {
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
        it.in.loop(1){true}{it.object.token in ['T_FILENAME', 'T_NAMESPACE', 'T_CLASS', 'T_FUNCTION']}.each{
            if (it.token == 'T_FILENAME') {
                file = it.fullcode;
            } else if (it.token == 'T_NAMESPACE') {
                rnamespace = it.out('NAME').next().code;
            } else if (it.token == 'T_CLASS') {
                rclass = it.out('NAME').next().code;
            } else if (it.token == 'T_FUNCTION') {
                rfunction = it.out('NAME').next().code;
            } 
        }
        m = ['fullcode':it.fullcode, 'file':file, 'line':it.line, 'namespace':rnamespace, 'class':rclass, 'function':rfunction ];
    }
}.transform{ m; }

GREMLIN;
                    $res = gremlin_query($query);
                    $res = $res->results;
                    
                    foreach($res as $result) {
                        if (!is_object($result)) { 
                            $this->log->log("Object expected but not found\n".print_r($result)."\n");
                            continue;
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
