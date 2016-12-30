<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    
    private $stmtResults       = null;
    private $stmtResultsCounts = null;
    private $cleanResults      = null;
    
    private $rounds            = 0;
    private $sqliteFile        = null;
    private $sqliteFileFinal   = null;
    
    const WAITING_LOOP = 1000;
    
    public function run() {
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project)) {
            throw new NoSuchProject($this->config->project);
        }

        $res = $this->gremlin->query('g.V().hasLabel("Project").values("fullcode")');
        if ($res->results[0] !== $this->config->project) {
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
        Analyzer::$gremlinStatic = $this->gremlin;
        
        if ($this->config->update === true) {
            copy($this->sqliteFileFinal, $this->sqliteFile);
            $sqlite = new \Sqlite3($this->sqliteFile);
        } else {
            $sqlite = new \Sqlite3($this->sqliteFile);
            $this->getAtomCounts($sqlite);

            $this->collectStructures($sqlite);
            $this->collectLiterals($sqlite);

            $sqlite->query('CREATE TABLE themas (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   thema STRING
                                                  )');
        
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
                                                           count INTEGER DEFAULT -6)');
            display('Inited tables');
        }

        $sqlQuery = <<<SQL
DELETE FROM results WHERE analyzer = :analyzer
SQL;
        $this->cleanResults = $sqlite->prepare($sqlQuery);

        $sqlQuery = <<<SQL
REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES ( NULL, :fullcode, :file,  :line,  :namespace,  :class,  :function,  :analyzer,  :severity )
SQL;
        $this->stmtResults = $sqlite->prepare($sqlQuery);

        $sqlQuery = <<<SQL
REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, :class, :count )
SQL;
        $this->stmtResultsCounts = $sqlite->prepare($sqlQuery);

        $themes = array();
        if ($this->config->thema !== null) {
            $thema = $this->config->thema;
            $themes = Analyzer::getThemeAnalyzers($thema);
            if (empty($themes)) {
                $r = Analyzer::getSuggestionThema($thema);
                if (count($r) > 0) {
                    echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
                }
                throw new NoSuchThema($thema);
            }
            display('Processing thema : '.$thema);
        } elseif ($this->config->program !== null) {
            $analyzer = $this->config->program;
            if (!Analyzer::getClass($analyzer)) {
                throw new NoSuchAnalyzer($analyzer);
            }            
            $themes = array($analyzer);
            display('Processing one analyzer : '.$analyzer);
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
                display( $thema." : No\n");
            }
        }

        $this->log->log( 'Still '.count($themes)." to be processed\n");
        display('Still '.count($themes)." to be processed\n");
        if (count($themes) === 0) {
            if ($this->config->thema !== null) {
                $sqlite->query('INSERT INTO themas ("id", "thema") VALUES ( NULL, "'.$this->config->thema.'")');
            }
        }

        $this->finish();
    }
        
    private function processResults($class, $count) {
        $this->cleanResults->bindValue(':analyzer', $class, \SQLITE3_TEXT);
        $this->cleanResults->execute();

        $this->stmtResultsCounts->bindValue(':class', $class, \SQLITE3_TEXT);
        $this->stmtResultsCounts->bindValue(':count', $count, \SQLITE3_INTEGER);

        $result = $this->stmtResultsCounts->execute();
        
        $this->log->log( "$class : $count\n");
        // No need to go further
        if ($count <= 0) {
            return;
        }

        $this->stmtResults->bindValue(':class', $class, \SQLITE3_TEXT);
        $analyzer = Analyzer::getInstance($class);
        
        $res = $analyzer->getDump();
        
        $saved = 0;
        $severity = $analyzer->getSeverity( );

        foreach($res as $id => $result) {
            if (!$result instanceof \Stdclass) {
                $this->log->log("Object expected but not found\n".print_r($result, true)."\n");
                continue;
            }
            
            if (!isset($result->class)) {
                continue;
            }
            
            $this->stmtResults->bindValue(':fullcode', $result->fullcode,      \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':file',     $result->file,          \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':line',     $result->line,          \SQLITE3_INTEGER);
            $this->stmtResults->bindValue(':namespace',$result->{'namespace'}, \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':class',    $result->class,         \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':function', $result->function,      \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':analyzer', $class,                 \SQLITE3_TEXT);
            $this->stmtResults->bindValue(':severity', $severity,              \SQLITE3_TEXT);
            
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

        
        foreach(Token::$ATOMS as $atom) {
            $query = 'g.V().hasLabel("'.$atom.'").count()';
            $res = $this->gremlin->query($query);
            if (!$res instanceof \stdClass || !isset($res->results)) {
                $this->log->log( "Couldn't run the query and get a result : \n" .
                     'Query : ' . $query . " \n".
                     print_r($res, true));
                continue ;
            }

            $res = $res->results;
            $insert->bindValue(':atom', $atom ,   \SQLITE3_TEXT);
            $insert->bindValue(':count', $res[0], \SQLITE3_INTEGER);
            $insert->execute();
        }
    }
    
    private function finish() {
        $this->stmtResultsCounts->bindValue(':class', 'Project/Dump', \SQLITE3_TEXT);
        $this->stmtResultsCounts->bindValue(':count', $this->rounds, \SQLITE3_INTEGER);

        $this->stmtResultsCounts->execute();
        
        rename($this->sqliteFile, $this->sqliteFileFinal);
        
        $this->removeSnitch();
    }
    
    private function collectStructures($sqlite) {

        // Name spaces
        $sqlite->query('DROP TABLE IF EXISTS namespaces');
        $sqlite->query('CREATE TABLE namespaces (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   namespace STRING
                                                 )');
        $sqlite->query('INSERT INTO namespaces VALUES ( 1, "Global")');

        $sqlQuery = <<<SQL
INSERT INTO namespaces ("id", "namespace") 
             VALUES ( NULL, :namespace)
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Namespace").out("NAME").map{ ['name' : it.get().value("fullcode")] };
GREMLIN
;
        $res = $this->gremlin->query($query);
        $res = $res->results;
        
        $namespacesId = array('' => 1);
        $total = 0;
        foreach($res as $row) {
            if (isset($namespacesId['\\'.$row->name])) {
                continue;
            }

            $stmt->bindValue(':namespace',   $row->name,            \SQLITE3_TEXT);
            $stmt->execute();
            $namespacesId['\\'.strtolower($row->name)] = $sqlite->lastInsertRowID();

            ++$total;
        }
        display("$total namespaces\n");

        // Ids for Classes, Interfaces and Traits
        $citId = array();

        // Classes
        $sqlite->query('DROP TABLE IF EXISTS cit');
        $sqlite->query('CREATE TABLE cit (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name STRING,
                                                   abstract INTEGER,
                                                   final INTEGER,
                                                   type TEXT,
                                                   extends TEXT DEFAULT "",
                                                   namespaceId INTEGER DEFAULT 1
                                                 )');

        $sqlite->query('CREATE TABLE cit_implements (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                       implementing INTEGER,
                                                       implements INTEGER,
                                                       type    TEXT
                                                 )');

        $sqlQuery = <<<SQL
INSERT INTO cit ("id", "name", "namespaceId", "abstract", "final", "extends", "type") 
             VALUES ( NULL, :class, :namespaceId, :abstract, :final, :extends, "class")
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Class")
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").sideEffect{ implementList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ useList = []; }.where(__.out("BLOCK").out("ELEMENT").hasLabel("Use").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'abstract':it.get().vertices(OUT, "ABSTRACT").any(),
         'final':it.get().vertices(OUT, "FINAL").any(),
         'extends':extendList,
         'implements':implementList,
         'uses':useList
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

        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row->fullnspath);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $stmt->bindValue(':class',       $row->name,            \SQLITE3_TEXT);
            $stmt->bindValue(':namespaceId', $namespaceId,          \SQLITE3_INTEGER);
            $stmt->bindValue(':abstract',    (int) $row->abstract , \SQLITE3_INTEGER);
            $stmt->bindValue(':final',       (int) $row->final,     \SQLITE3_INTEGER);

            $stmt->execute();
            $citId[$row->fullnspath] = $sqlite->lastInsertRowID();

            // Get extends
            if (!empty($row->extends)) {
                if (isset($extendsId[$row->extends[0]])) {
                    $extendsId[$row->extends[0]][] = $citId[$row->fullnspath];
                } else {
                    $extendsId[$row->extends[0]] = array($citId[$row->fullnspath]);
                }
            }

            // Get implements
            if (!empty($row->implements)) {
                $implementsId[$citId[$row->fullnspath]] = $row->implements;
            }
            
            // Get use
            if (!empty($row->uses)) {
                $usesId[$citId[$row->fullnspath]] = $row->uses;
            }
            ++$total;
        }
        
        display("$total classes\n");

        // Interfaces
        $sqlQuery = <<<SQL
INSERT INTO cit ("id", "name", "namespaceId", "abstract", "final", "type") 
             VALUES ( NULL, :name, :namespaceId, 0, 0, "interface")
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Interface")
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").sideEffect{ implementList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'extends':extendList,
         'implements':implementList
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

            $stmt->bindValue(':name',       $row->name,            \SQLITE3_TEXT);
            $stmt->bindValue(':namespaceId', $namespaceId,          \SQLITE3_INTEGER);

            $stmt->execute();
            $citId[$row->fullnspath] = $sqlite->lastInsertRowID();

            // Get extends
            if (!empty($row->extends)) {
                if (isset($extendsId[$row->extends[0]])) {
                    $extendsId[$row->extends[0]][] = $citId[$row->fullnspath];
                } else {
                    $extendsId[$row->extends[0]] = array($citId[$row->fullnspath]);
                }
            }

            // Get implements
            if (!empty($row->implements)) {
                $implementsId[$citId[$row->fullnspath]] = $row->implements;
            }
            ++$total;
        }
        display("$total interfaces\n");

        // Traits
        $sqlQuery = <<<SQL
INSERT INTO cit ("id", "name", "namespaceId", "abstract", "final", "type") 
             VALUES ( NULL, :name, :namespaceId, 0, 0, "trait")
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Trait")
.sideEffect{ useList = []; }.where(__.out("BLOCK").out("ELEMENT").hasLabel("Use").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("code"),
         'uses':useList
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

            $stmt->bindValue(':name',       $row->name,             \SQLITE3_TEXT);
            $stmt->bindValue(':namespaceId', $namespaceId,          \SQLITE3_INTEGER);

            $stmt->execute();
            $citId[$row->fullnspath] = $sqlite->lastInsertRowID();
            ++$total;
        }
        display("$total traits\n");

        // Manage extends
        $sqlQuery = <<<SQL
UPDATE cit SET extends = :class WHERE id = :id
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $total = 0;
        foreach($extendsId as $exId => $ids) {
            if (isset($citId[$exId])) {
                foreach($ids as $id) {
                    $stmt->bindValue(':id',       $id,           \SQLITE3_INTEGER);
                    $stmt->bindValue(':class',    $citId[$exId], \SQLITE3_INTEGER);
                
                    $stmt->execute();
                    ++$total;
                }
            } // Else ignore. Not in the project
        }
        display("$total extends \n");

        // Manage implements
        $sqlQuery = <<<SQL
INSERT INTO cit_implements ("id", "implementing", "implements", "type") 
             VALUES ( NULL, :implementing, :implements, :type)
SQL;
        $stmtImplements = $sqlite->prepare($sqlQuery);

        $total = 0;
        $stmtImplements->bindValue(':type',   'implements',          \SQLITE3_TEXT);
        foreach($implementsId as $id => $implementsFNP) {
            foreach($implementsFNP as $fnp) {
                $stmtImplements->bindValue(':implementing',   $id,          \SQLITE3_INTEGER);
                if (isset($citId[$fnp])) {
                    $stmtImplements->bindValue(':implements', $citId[$fnp], \SQLITE3_INTEGER);
                    
                    $stmtImplements->execute();
                    ++$total;
                } // Else ignore. Not in the project
            }
        }
        display("$total implements \n");

        // Manage use (traits)
        // Same SQL than for implements

        $total = 0;
        $stmtImplements->bindValue(':type',   'use',          \SQLITE3_TEXT);
        foreach($usesId as $id => $usesFNP) {
            foreach($usesFNP as $fnp) {
                $stmtImplements->bindValue(':implementing',   $id,          \SQLITE3_INTEGER);
                if (substr($fnp, 0, 2) == '\\\\') {
                    $fnp = substr($fnp, 2);
                }
                if (isset($citId[$fnp])) {
                    $stmtImplements->bindValue(':implements', $citId[$fnp], \SQLITE3_INTEGER);
                    
                    $stmtImplements->execute();
                    ++$total;
                } // Else ignore. Not in the project
            }
        }
        display("$total uses \n");

        // Methods
        $sqlite->query('DROP TABLE IF EXISTS methods');
        $sqlite->query('CREATE TABLE methods (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                method INTEGER,
                                                citId INTEGER,
                                                static INTEGER,
                                                final INTEGER,
                                                abstract INTEGER,
                                                visibility INTEGER
                                                 )');

        $sqlQuery = <<<SQL
INSERT INTO methods ("id", "method", "citId", "static", "final", "abstract", "visibility") 
             VALUES ( NULL, :method, :citId, :static, :final, :abstract, :visibility)
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Function")
.where( __.out("NAME").hasLabel("Void").count().is(eq(0)) )
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface", "Trait").sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
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

            $stmt->bindValue(':method',    $row->name,                   \SQLITE3_TEXT);
            $stmt->bindValue(':citId',     $citId[$row->class],          \SQLITE3_INTEGER);
            $stmt->bindValue(':static',    (int) $row->static,           \SQLITE3_INTEGER);
            $stmt->bindValue(':final',     (int) $row->final,            \SQLITE3_INTEGER);
            $stmt->bindValue(':abstract',  (int) $row->abstract,         \SQLITE3_INTEGER);
            $stmt->bindValue(':visibility',$visibility,                  \SQLITE3_TEXT);

            $result = $stmt->execute();
            ++$total;
        }
        display("$total methods\n");

        // Properties
        $sqlite->query('DROP TABLE IF EXISTS properties');
        $sqlite->query('CREATE TABLE properties (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                property INTEGER,
                                                citId INTEGER,
                                                visibility INTEGER,
                                                static INTEGER,
                                                value TEXT
                                                 )');

        $sqlQuery = <<<SQL
INSERT INTO properties ("id", "property", "citId", "visibility", "value", "static") 
             VALUES ( NULL, :property, :citId, :visibility, :value, :static)
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN

g.V().hasLabel("Ppp")
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface").sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
.filter{ classe != '';} // Removes functions, keeps methods
.out('PPP')
.map{ 
    if (it.get().label() == 'Variable') { 
        name = it.get().value("code");
        v = ''; 
    } else { 
        name = it.get().vertices(OUT, 'LEFT').next().value("code");
        v = it.get().vertices(OUT, 'RIGHT').next().value("code");
    }

    x = ['name': name,
         'value': v,
         'static':it.get().vertices(OUT, "STATIC").any(),

         'public':it.get().vertices(OUT, "PUBLIC").any(),
         'protected':it.get().vertices(OUT, "PROTECTED").any(),
         'private':it.get().vertices(OUT, "PRIVATE").any(),
         'var':it.get().vertices(OUT, "VAR").any(),
         
         'class': classe

         ];
}

GREMLIN
;
        $res = $this->gremlin->query($query);
        $res = $res->results;
        
        $total = 0;
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

            $stmt->bindValue(':property',  $row->name,                   \SQLITE3_TEXT);
            $stmt->bindValue(':citId',     $citId[$row->class],          \SQLITE3_INTEGER);
            $stmt->bindValue(':value',     $row->value,                  \SQLITE3_TEXT);
            $stmt->bindValue(':static',    (int) $row->static,           \SQLITE3_INTEGER);
            $stmt->bindValue(':visibility',$visibility,                  \SQLITE3_TEXT);

            $result = $stmt->execute();
            ++$total;
        }
        display("$total properties\n");

        // Constants
        $sqlite->query('DROP TABLE IF EXISTS constants');
        $sqlite->query('CREATE TABLE constants (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                constant INTEGER,
                                                citId INTEGER,
                                                value TEXT
                                                 )');

        $sqlQuery = <<<SQL
INSERT INTO constants ("id", "constant", "citId", "value") 
             VALUES ( NULL, :constant, :citId, :value)
SQL;
        $stmt = $sqlite->prepare($sqlQuery);

        $query = <<<GREMLIN
g.V().hasLabel("Const")
.sideEffect{ classe = ''; }.where(__.in("ELEMENT").in("BLOCK").hasLabel("Class", "Interface").sideEffect{ classe = it.get().value("fullnspath"); }.fold() )
.filter{ classe != '';} // Removes functions, keeps methods
.out('CONST')
.map{ 
    x = ['name': it.get().vertices(OUT, 'LEFT').next().value("code"),
         'value': it.get().vertices(OUT, 'RIGHT').next().value("code"),
         
         'class': classe
         ];
}

GREMLIN
;
        $res = $this->gremlin->query($query);
        $res = $res->results;
        
        $total = 0;
        foreach($res as $row) {
            $stmt->bindValue(':constant',  $row->name,                   \SQLITE3_TEXT);
            $stmt->bindValue(':citId',   $citId[$row->class],            \SQLITE3_INTEGER);
            $stmt->bindValue(':value',     $row->value,                  \SQLITE3_TEXT);

            $result = $stmt->execute();
            ++$total;
        }
        display("$total constants\n");
    }

    private function collectLiterals($sqlite) {
        $types = array('Integer', 'Real', 'String', 'Heredoc', 'Array');
        
        foreach($types as $type) {
            $sqlite->query('DROP TABLE IF EXISTS literal'.$type);
            $sqlite->query('CREATE TABLE literal'.$type.' (  
                                                   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name STRING,
                                                   file STRING,
                                                   line INTEGER
                                                 )');
                                                 
            $stmt = $sqlite->prepare('INSERT INTO literal'.$type.' (name, file, line) VALUES(:name, :file, :line)');

            if ($type == 'Array') {
                $filter = 'hasLabel("Functioncall").has("fullnspath", "\\\\array")';
            } else {
                $filter = 'hasLabel("'.$type.'")';
            }
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
            $res = $res->results;

            $total = 0;
            foreach($res as $value => $row) {
                $stmt->bindValue(':name', $row->name, \SQLITE3_TEXT);
                $stmt->bindValue(':file', $row->file, \SQLITE3_TEXT);
                $stmt->bindValue(':line', $row->line, \SQLITE3_INTEGER);
                $stmt->execute();
                ++$total;
            }
            display( "literal$type : $total\n");
        }
    }
}

?>
