<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Analyzer\Analyzer;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchThema;
use Exakat\Exceptions\NotProjectInGraph;
use Exakat\Exceptions\NeedsAnalysisThema;
use Exakat\Tokenizer\Token;

class Dump extends Tasks {
    const CONCURENCE = self::DUMP;

    private $sqlite            = null;

    private $rounds            = 0;
    private $sqliteFile        = null;
    private $sqliteFileFinal   = null;

    const WAITING_LOOP = 1000;

    public function run() {
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project)) {
            throw new NoSuchProject($this->config->project);
        }

        $res = $this->gremlin->query('g.V().hasLabel("Project").values("fullcode")');
        if (!isset($res->results[0]) || $res->results[0] !== $this->config->project) {
            throw new NotProjectInGraph($this->config->project, $res->results[0]);
        }

        // move this to .dump.sqlite then rename at the end, or any imtermediate time
        // Mention that some are not yet arrived in the snitch
        $this->sqliteFile = $this->config->projects_root.'/projects/'.$this->config->project.'/.dump.sqlite';
        $this->sqliteFileFinal = $this->config->projects_root.'/projects/'.$this->config->project.'/dump.sqlite';
        if (file_exists($this->sqliteFile)) {
            unlink($this->sqliteFile);
            display('Removing old .dump.sqlite');
        }

        $this->addSnitch();

        Analyzer::initDocs();

        if ($this->config->update === true) {
            copy($this->sqliteFileFinal, $this->sqliteFile);
            $this->sqlite = new \Sqlite3($this->sqliteFile);
        } else {
            $this->sqlite = new \Sqlite3($this->sqliteFile);

            $query = <<<SQL
CREATE TABLE themas (  id    INTEGER PRIMARY KEY AUTOINCREMENT,
                       thema STRING
                    )
SQL;
            $this->sqlite->query($query);

            $query = <<<SQL
CREATE TABLE results (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                        fullcode STRING,
                        file STRING,
                        line INTEGER,
                        namespace STRING,
                        class STRING,
                        function STRING,
                        analyzer STRING,
                        severity STRING
                     )
SQL;
            $this->sqlite->query($query);

            $query = <<<SQL
CREATE TABLE resultsCounts ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                             analyzer STRING,
                             count INTEGER DEFAULT -6
                           )
SQL;
            $this->sqlite->query($query);

            display('Inited tables');
        }
        
        if ($this->config->collect === true) {
            display('Collecting data');
            $this->getAtomCounts();

            $this->collectStructures();
            $this->collectLiterals();
            $this->collectFilesDependencies();
            display('Collecting data finished');
        }

        $themes = array();
        if ($this->config->thema !== null) {
            $thema = $this->config->thema;
            $themes = Analyzer::getThemeAnalyzers($thema);
            if (empty($themes)) {
                $r = Analyzer::getSuggestionThema($thema);
                if (!empty($r)) {
                    echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
                }
                throw new NoSuchThema($thema);
            }
            display('Processing thema : '.$thema);
        } elseif ($this->config->program !== null) {
            $analyzer = $this->config->program;
            if(!is_array($analyzer)) {
                $themes = array($analyzer);
            } else {
                $themes = $analyzer;
            }

            foreach($themes as $a) {
                if (!Analyzer::getClass($a)) {
                    throw new NoSuchAnalyzer($a);
                }
            }
            display('Processing '.count($themes).' analyzer'.(count($themes) > 1 ? 's' : '').' : '.implode(', ', $themes));
        }

        $sqlitePath = $this->config->projects_root.'/projects/'.$this->config->project.'/datastore.sqlite';

        $counts = array();
        $datastore = new \Sqlite3($sqlitePath, \SQLITE3_OPEN_READONLY);
        $datastore->busyTimeout(5000);
        $res = $datastore->query('SELECT * FROM analyzed');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }
        $this->log->log( 'count analyzed : '.count($counts)."\n");
        $this->log->log( 'counts '.implode(', ', $counts)."\n");
        $datastore->close();
        unset($datastore);

        foreach($themes as $id => $thema) {
            if (isset($counts[$thema])) {
                display( $thema.' : '.($counts[$thema] >= 0 ? 'Yes' : 'N/A')."\n");
                $this->processResults($thema, $counts[$thema]);
                unset($themes[$id]);
            } else {
                display( $thema.' : No'.PHP_EOL);
            }
        }

        $this->log->log( 'Still '.count($themes)." to be processed\n");
        display('Still '.count($themes)." to be processed\n");
        if (count($themes) === 0) {
            if ($this->config->thema !== null) {
                $this->sqlite->query('INSERT INTO themas ("id", "thema") VALUES ( NULL, "'.$this->config->thema.'")');
            }
        }

        $this->finish();
    }

    private function processResults($class, $count) {
        $this->sqlite->query("DELETE FROM results WHERE analyzer = '$class'");

        $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, \''.$class.'\', '.(int) $count.')');

        $this->log->log( "$class : $count\n");
        // No need to go further
        if ($count <= 0) {
            return;
        }

        $analyzer = Analyzer::getInstance($class, $this->gremlin, $this->config);
        $res = $analyzer->getDump();
        if (!is_array($res)) {
            return;
        }

        $saved = 0;
        $severity = $analyzer->getSeverity( );

        $query = array();
        foreach($res as $id => $result) {
            if (!is_object($result)) {
                continue;
            }

            $query[] = "(null, '".$this->sqlite->escapeString($result->fullcode)."', '".$this->sqlite->escapeString($result->file)."', 
            ".$this->sqlite->escapeString($result->line).", '".$this->sqlite->escapeString($result->{'namespace'})."', 
            '".$this->sqlite->escapeString($result->class)."', '".$this->sqlite->escapeString($result->function)."',
            '".$this->sqlite->escapeString($class)."','".$this->sqlite->escapeString($severity)."')";
            ++$saved;
        }

        if (!empty($query)) {
            $query = 'REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }

        $this->log->log("$class : dumped $saved");


        if ($count != $saved) {
            display("$saved results saved, $count expected for $class\n");
        } else {
            display("All $saved results saved for $class\n");
        }
    }

    private function getAtomCounts() {
        $this->sqlite->query('DROP TABLE IF EXISTS atomsCounts');
        $this->sqlite->query('CREATE TABLE atomsCounts (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                    atom STRING,
                                                    count INTEGER
                                              )');

        $query = 'g.V().groupCount("b").by(label).cap("b");';
        $res = $this->gremlin->query($query);

        $query = array();
        foreach($res->results[0] as $atom => $count) {
            $query[] = "(null, '$atom', $count)";
        }
        
        $query = 'INSERT INTO atomsCounts ("id", "atom", "count") VALUES '.join(', ', $query);
        $this->sqlite->query($query);
    }

    private function finish() {
        $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, \'Project/Dump\', '.$this->rounds.')');

        $this->collectDatastore();

        // Redo each time so we update the final counts
        $res = $this->gremlin->query('g.V().count()');
        $res = $res->results;
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total nodes", '.$res[0].')');

        $res = $this->gremlin->query('g.E().count()');
        $res = $res->results;
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total edges", '.$res[0].')');

        $res = $this->gremlin->query('g.V().properties().count()');
        $res = $res->results;
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total properties", '.$res[0].')');

        rename($this->sqliteFile, $this->sqliteFileFinal);

        $this->removeSnitch();
    }

    private function collectDatastore() {
        $datastorePath = $this->config->projects_root.'/projects/'.$this->config->project.'/datastore.sqlite';
        $this->sqlite->query('ATTACH "'.$datastorePath.'" AS datastore');

        $tables = array('analyzed',
                        'compilation52',
                        'compilation53',
                        'compilation54',
                        'compilation55',
                        'compilation56',
                        'compilation70',
                        'compilation71',
                        'compilation72',
                        'composer',
                        'configFiles',
                        'externallibraries',
                        'files',
                        'hash',
                        'hashAnalyzer',
                        'ignoredFiles',
                        'shortopentag',
                        'tokenCounts',
                        );
        $query = "SELECT name, sql FROM datastore.sqlite_master WHERE type='table' AND name in ('".implode("', '", $tables)."');";
        $existingTables = $this->sqlite->query($query);

        while($table = $existingTables->fetchArray(\SQLITE3_ASSOC)) {
            $createTable = $table['sql'];
            $createTable = str_replace('CREATE TABLE ', 'CREATE TABLE IF NOT EXISTS ', $createTable);

            $this->sqlite->query($createTable);
            $this->sqlite->query('REPLACE INTO '.$table['name'].' SELECT * FROM datastore.'.$table['name']);
        }
    }

    private function collectStructures() {

        // Name spaces
        $this->sqlite->query('DROP TABLE IF EXISTS namespaces');
        $this->sqlite->query('CREATE TABLE namespaces (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   namespace STRING
                                                 )');
        $this->sqlite->query('INSERT INTO namespaces VALUES ( 1, "")');

        $query = <<<GREMLIN
g.V().hasLabel("Namespace").out("NAME").map{ ['name' : it.get().value("fullcode")] }.unique();
GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '\\".strtolower($this->sqlite->escapeString($row->name))."')";
            ++$total;
        }
        
        if (!empty($query)) {
            $query = 'INSERT INTO namespaces ("id", "namespace") VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }

        $query = 'SELECT id, lower(namespace) AS namespace FROM namespaces';
        $res = $this->sqlite->query($query);

        $namespacesId = array('' => 1);
        while($namespace = $res->fetchArray(\SQLITE3_ASSOC)) {
            $namespacesId[$namespace['namespace']] = $namespace['id'];
        }
        display("$total namespaces\n");

        // Classes
        $this->sqlite->query('DROP TABLE IF EXISTS cit');
        $this->sqlite->query('CREATE TABLE cit (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name STRING,
                                                   abstract INTEGER,
                                                   final INTEGER,
                                                   type TEXT,
                                                   extends TEXT DEFAULT "",
                                                   namespaceId INTEGER DEFAULT 1
                                                 )');

        $this->sqlite->query('DROP TABLE IF EXISTS cit_implements');
        $this->sqlite->query('CREATE TABLE cit_implements (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                             implementing INTEGER,
                                                             implements INTEGER,
                                                             type    TEXT
                                                 )');

        $query = <<<GREMLIN
g.V().hasLabel("Class")
.where(__.out("NAME").hasLabel("Void").count().is(eq(0)) )
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").sideEffect{ implementList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Use").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'abstract':it.get().vertices(OUT, "ABSTRACT").any(),
         'final':it.get().vertices(OUT, "FINAL").any(),
         'extends':extendList,
         'implements':implementList,
         'uses':useList,
         'type':'class'
         ];
}

GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        $extendsId = array();
        $implementsId = array();
        $usesId = array();
        
        $cit = array();
        $citId = array();
        $citCount = 0;

        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row->fullnspath);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $cit[] = $row;
            $citId[$row->fullnspath] = ++$citCount;
            
            ++$total;
        }

        display("$total classes\n");

        // Interfaces
        $query = <<<GREMLIN
g.V().hasLabel("Interface")
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").sideEffect{ implementList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'extends':extendList,
         'implements':implementList,
         'type':'interface',
         'abstract':0,
         'final':0
         ];
}
GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row->fullnspath);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $cit[] = $row;
            $citId[$row->fullnspath] = ++$citCount;

            ++$total;
        }

        display("$total interfaces\n");

        // Traits
        $query = <<<GREMLIN
g.V().hasLabel("Trait")
.sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Use").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'uses':useList,
         'type':'trait',
         'abstract':0,
         'final':0
         ];
}

GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row->fullnspath);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $cit[] = $row;
            $citId[$row->fullnspath] = ++$citCount;

            ++$total;
        }
        
        display("$total traits\n");
        
        if (!empty($cit)) {
            $query = array();
            
            foreach($cit as $row) {
                if (empty($row->extends)) {
                    $extends = "''";
                } elseif (isset($citId[$row->extends])) {
                    $extends = $citId[$row->extends];
                } else {
                    $extends = '"'.$this->sqlite->escapeString($row->extends).'"';
                }
                $namespace = preg_replace('/\\\\[^\\\\]*?$/', '', $row->fullnspath);
                $query[] = "(".$citId[$row->fullnspath].", '".$this->sqlite->escapeString($row->name)."', ".$namespacesId[$namespace].", ".(int) $row->abstract.",".(int) $row->final.", '"
                                .$row->type."', ".$extends.")";
            }

            if (!empty($query)) {
                $query = 'INSERT OR IGNORE INTO cit ("id", "name", "namespaceId", "abstract", "final", "type", "extends") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }

            $query = array();
            foreach($cit as $row) {
                if (empty($row->implements)) {
                    continue;
                }

                foreach($row->implements as $implements) {
                    if (isset($citId[$implements])) {
                        $query[] = "(null, ".$citId[$row->fullnspath].", $citId[$implements], 'implements')";
                    } else {
                        $query[] = "(null, ".$citId[$row->fullnspath].", '".$this->sqlite->escapeString($implements)."', 'implements')";
                    }
                }
            }

            if (!empty($query)) {
                $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }

            $query = array();
            foreach($cit as $row) {
                if (empty($row->uses)) {
                    continue;
                }

                foreach($row->uses as $uses) {
                    if (isset($citId[$uses])) {
                        $query[] = "(null, ".$citId[$row->fullnspath].", $citId[$uses], 'use')";
                    } else {
                        $query[] = "(null, ".$citId[$row->fullnspath].", '".$this->sqlite->escapeString($row->name)."', 'use')";
                    }
                }
            }

            if (!empty($query)) {
                $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
        }

        // Manage use (traits)
        // Same SQL than for implements

        $total = 0;
        $query = array();
        foreach($usesId as $id => $usesFNP) {
            foreach($usesFNP as $fnp) {
                if (substr($fnp, 0, 2) == '\\\\') {
                    $fnp = substr($fnp, 2);
                }
                if (isset($citId[$fnp])) {
                    $query[] = "(null, $id, $citId[$fnp], 'use')";

                    ++$total;
                } // Else ignore. Not in the project
            }
        }
        if (!empty($query)) {
            $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }
        display("$total uses \n");

        // Methods
        $this->sqlite->query('DROP TABLE IF EXISTS methods');
        $this->sqlite->query('CREATE TABLE methods (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                method INTEGER,
                                                citId INTEGER,
                                                static INTEGER,
                                                final INTEGER,
                                                abstract INTEGER,
                                                visibility STRING
                                                 )');

        
        $query = <<<GREMLIN
g.V().hasLabel("Method")
.where( __.out("NAME").hasLabel("Void").count().is(eq(0)) )
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface", "Trait")
                                    .where(__.out("NAME").hasLabel("Void").count().is(eq(0)) )
                                    .sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
.filter{ classe != '';} // Removes functions, keeps methods
.map{ 
    x = ['name': it.get().value("fullcode"),
         'abstract':it.get().vertices(OUT, "ABSTRACT").any(),
         'final':it.get().vertices(OUT, "FINAL").any(),
         'static':it.get().vertices(OUT, "STATIC").any(),

         'public':it.get().vertices(OUT, "PUBLIC").any(),
         'protected':it.get().vertices(OUT, "PROTECTED").any(),
         'private':it.get().vertices(OUT, "PRIVATE").any(),         
         'class': classe
         ];
}

GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        $query = array();
        foreach($res as $row) {
            if ($row->public) {
                $visibility = 'public';
            } elseif ($row->protected) {
                $visibility = 'protected';
            } elseif ($row->private) {
                $visibility = 'private';
            } else {
                $visibility = '';
            }

            if (!isset($citId[$row->class])) {
                continue;
            }
            $query[] = "(null, '".$this->sqlite->escapeString($row->name)."', ".$citId[$row->class].
                        ", ".(int) $row->static.", ".(int) $row->final.", ".(int) $row->abstract.", '".$visibility."')";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO methods ("id", "method", "citId", "static", "final", "abstract", "visibility") VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }

        display("$total methods\n");

        // Properties
        $this->sqlite->query('DROP TABLE IF EXISTS properties');
        $this->sqlite->query('CREATE TABLE properties (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                property INTEGER,
                                                citId INTEGER,
                                                visibility STRING,
                                                static INTEGER,
                                                value TEXT
                                                 )');

        $query = <<<GREMLIN

g.V().hasLabel("Ppp")
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface")
                                    .sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
.filter{ classe != '';} // Removes functions, keeps methods
.sideEffect{ 
    x = ['static':it.get().vertices(OUT, "STATIC").any(),

         'public':it.get().vertices(OUT, "PUBLIC").any(),
         'protected':it.get().vertices(OUT, "PROTECTED").any(),
         'private':it.get().vertices(OUT, "PRIVATE").any(),
         'var':it.get().vertices(OUT, "VAR").any(),
         
         'class': classe];
}
.out('PPP')
.map{ 
    if (it.get().label() == 'Propertydefinition') { 
        name = it.get().value("code");
        v = ''; 
    } else { 
        name = it.get().vertices(OUT, 'LEFT').next().value("code");
        v = it.get().vertices(OUT, 'RIGHT').next().value("fullcode");
    }

    x['name'] = name;
    x['value'] = v;
    x;
}

GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        $query = array();
        foreach($res as $row) {
            if ($row->public) {
                $visibility = 'public';
            } elseif ($row->protected) {
                $visibility = 'protected';
            } elseif ($row->private) {
                $visibility = 'private';
            } elseif ($row->var) {
                $visibility = '';
            } else {
                continue;
            }

            // If we haven't found any definition for this class, just ignore it.
            if (!isset($citId[$row->class])) {
                continue;
            }
            $query[] = "(null, '".$this->sqlite->escapeString($row->name)."', ".$citId[$row->class].
                        ", '".$visibility."', '".$this->sqlite->escapeString($row->value)."', ".(int) $row->static.")";

            ++$total;
        }
        if (!empty($query)) {
            $query = 'INSERT INTO properties ("id", "property", "citId", "visibility", "value", "static") VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }
        
        display("$total properties\n");

        // Constants
        $this->sqlite->query('DROP TABLE IF EXISTS constants');
        $this->sqlite->query('CREATE TABLE constants (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                constant INTEGER,
                                                citId INTEGER,
                                                value TEXT
                                                 )');

        $query = <<<GREMLIN
g.V().hasLabel("Const")
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface").sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
.filter{ classe != '';} // Removes functions, keeps methods
.out('CONST')
.map{ 
    x = ['name': it.get().vertices(OUT, 'NAME').next().value("code"),
         'value': it.get().vertices(OUT, 'VALUE').next().value("fullcode"),
         'class': classe
         ];
}

GREMLIN
        ;
        $res = $this->gremlin->query($query);
        $res = $res->results;

        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '".$this->sqlite->escapeString($row->name)."', ".$citId[$row->class].", '".$this->sqlite->escapeString($row->value)."')";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO constants ("id", "constant", "citId", "value") VALUES '.join(', ', $query);
            $this->sqlite->query($query);
        }
        display("$total constants\n");
    }

    private function collectLiterals() {
        $types = array('Integer', 'Real', 'String', 'Heredoc', 'Arrayliteral');

        foreach($types as $type) {
            $this->sqlite->query('DROP TABLE IF EXISTS literal'.$type);
            $this->sqlite->query('CREATE TABLE literal'.$type.' (  
                                                   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name STRING,
                                                   file STRING,
                                                   line INTEGER
                                                 )');

            $filter = 'hasLabel("'.$type.'")';

            $b = microtime(true);
            $query = <<<GREMLIN

g.V().$filter.has('constant', true)
.sideEffect{ name = it.get().value("fullcode");
             line = it.get().value('line');
             file='None'; 
             }
.until( hasLabel('Project') ).repeat( 
    __.in()
      .sideEffect{ if (it.get().label() == 'File') { file = it.get().value('fullcode')} }
       )
.map{ 
    x = ['name': name,
         'file': file,
         'line': line
         ];
}

GREMLIN
            ;
            $res = $this->gremlin->query($query);
            if (!($res instanceof \stdClass)) {
                continue;
            }
            $res = $res->results;

            $total = 0;
            $query = array();
            foreach($res as $value => $row) {
                $query[] = "('".$this->sqlite->escapeString($row->name)."','".$this->sqlite->escapeString($row->file)."',".$row->line.')';
                ++$total;
                if ($total % 10000 === 0) {
                    $query = 'INSERT INTO literal'.$type.' (name, file, line) VALUES '.join(', ', $query);
                    $this->sqlite->query($query);
                    $query = array();
                }
            }
            
            if (!empty($query)) {
                $query = 'INSERT INTO literal'.$type.' (name, file, line) VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display( "literal$type : $total\n");
        }

       $this->sqlite->query('DROP TABLE IF EXISTS stringEncodings');
       $this->sqlite->query('CREATE TABLE stringEncodings (  
                                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                                              encoding STRING,
                                              block STRING,
                                              CONSTRAINT "encoding" UNIQUE (encoding, block)
                                            )');

            $query = <<<GREMLIN
            
g.V().hasLabel('String').map{ x = ['encoding':it.get().values('encoding')[0]];
    if (it.get().values('block').size() != 0) {
        x['block'] = it.get().values('block')[0];
    }
    x;
}

GREMLIN;
        $res = $this->gremlin->query($query);
        if (!($res instanceof \stdClass)) {
            return;
        }
        $res = $res->results;
        
        $total = 0;
        $query = array();
        foreach($res as $value => $row) {
            if (isset($row->block)){
                $query[] = "('$row->encoding', '$row->block')";
            } else {
                $query[] = "('$row->encoding', '')";
            }
        }
       
       if (!empty($query)) {
           $query = 'REPLACE INTO stringEncodings ("encoding", "block") VALUES '.join(', ', $query);
        print $query;
           $this->sqlite->query($query);
           echo $this->sqlite->lastErrorMsg();
       }
    }

    private function collectFilesDependencies() {
        $this->sqlite->query('DROP TABLE IF EXISTS filesDependencies');
        $this->sqlite->query('CREATE TABLE filesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                               including STRING,
                                                               included STRING,
                                                               type STRING
                                                 )');

        // Direct inclusion
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Include")).times(15)
                   .hasLabel("Include").in("NAME").as("include")
                   .select("file", "include").by("fullcode").by("fullcode")';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $includes = $res->results;

            foreach($includes as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'INCLUDE')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($includes)." inclusions ");
        }

        // Finding extends and implements
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Class", "Interface")).times(15)
                   .hasLabel("Class", "Interface").outE().hasLabel("EXTENDS", "IMPLEMENTS").as("type").inV().in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "type", "include").by("fullcode").by(label()).by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $extends = $res->results;

            foreach($extends as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', '".$link->type."')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($extends)." extends for classes ");
        }

        // Finding extends for interfaces
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Interface")).times(15)
                   .hasLabel("Interface").out("EXTENDS").in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "include").by("fullcode").by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $extends = $res->results;

            foreach($extends as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'EXTENDS')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($extends)." extends for interfaces ");
        }

        // traits
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Class", "Trait")).times(15)
                   .hasLabel("Class", "Trait").out("USE").hasLabel("Use").out("USE").in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "include").by("fullcode").by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $uses = $res->results;

            foreach($uses as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'USE')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($extends)." use ");
        }

        // Functioncall()
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Functioncall")).times(15)
                   .hasLabel("Functioncall").in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "include").by("fullcode").by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $functioncalls = $res->results;

            foreach($functioncalls as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'FUNCTIONCALL')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($functioncalls)." functioncall ");
        }

        // constants
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Identifier")).times(15)
                   .hasLabel("Identifier").where( __.in("NAME", "CLASS", "PROPERTY", "AS", "CONSTANT", "TYPEHINT", "EXTENDS", "USE", "IMPLEMENTS", "INDEX" ).count().is(eq(0)) ).in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "include").by("fullcode").by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $constants = $res->results;

            foreach($constants as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'CONSTANT')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($constants)." constants ");
        }

        // New
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("New")).times(15)
                   .hasLabel("New").out("NEW").in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "include").by("fullcode").by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $news = $res->results;

            foreach($news as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', 'NEW')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($news)." new ");
        }

        // static calls (property, constant, method)
        $query = 'g.V().hasLabel("File").as("file")
                   .repeat( out() ).emit(hasLabel("Staticconstant", "Staticmethodcall", "Staticproperty")).times(15)
                   .hasLabel("Staticconstant", "Staticmethodcall", "Staticproperty").as("type").out("CLASS").in("DEFINITION")
                   .repeat( __.in() ).emit(hasLabel("File")).times(15).hasLabel("File")
                   .as("include")
                   .select("file", "type", "include").by("fullcode").by(label()).by("fullcode")
                   ';
        $res = $this->gremlin->query($query);
        $query = array();
        if (isset($res->results)) {
            $statics = $res->results;

            foreach($statics as $link) {
                $query[] = "(null, '".$this->sqlite->escapeString($link->file)."', '".$this->sqlite->escapeString($link->include)."', '".strtoupper($link->type)."')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES '.join(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($statics)." static calls CPM");
        }

    }
}

?>
