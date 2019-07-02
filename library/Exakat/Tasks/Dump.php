<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
use Exakat\Log;
use Exakat\GraphElements;
use Exakat\Analyzer\Analyzer;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchThema;
use Exakat\Exceptions\NotProjectInGraph;
use Exakat\Graph\Graph;
use Exakat\Reports\Helpers\Docs;
use Exakat\Query\Query;

class Dump extends Tasks {
    const CONCURENCE = self::DUMP;

    private $sqlite             = null;

    private $sqliteFile         = null;
    private $sqliteFileFinal    = null;
    private $sqliteFilePrevious = null;
    
    private $files = array();
    
    protected $logname = self::LOG_NONE;
    
    private $linksDown = '';

    const WAITING_LOOP = 1000;

    public function __construct(Graph $gremlin, Config $config, $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subTask);
        
        $this->log = new Log('dump',
                             $this->config->project_dir);

        $this->linksDown = GraphElements::linksAsList();
    }

    public function run() {
        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        $projectInGraph = $this->gremlin->query('g.V().hasLabel("Project").values("code")')
                                        ->toArray();
        if (empty($projectInGraph)) {
            throw new NoSuchProject($this->config->project);
        }
        $projectInGraph = $projectInGraph[0];
        
        if ($projectInGraph !== $this->config->project) {
            throw new NotProjectInGraph($this->config->project, $projectInGraph);
        }
        
        // move this to .dump.sqlite then rename at the end, or any imtermediate time
        // Mention that some are not yet arrived in the snitch
        $this->sqliteFile         = $this->config->dump_tmp;
        $this->sqliteFilePrevious = $this->config->dump_previous;
        $this->sqliteFileFinal    = $this->config->dump;
        if (file_exists($this->sqliteFile)) {
            unlink($this->sqliteFile);
            display('Removing old .dump.sqlite');
        }
        $this->addSnitch();

        if ($this->config->update === true && file_exists($this->sqliteFileFinal)) {
            copy($this->sqliteFileFinal, $this->sqliteFile);
            $this->sqlite = new \Sqlite3($this->sqliteFile);
        } else {
            $this->initDump();
        }
        
        if ($this->config->collect === true) {
            display('Collecting data');

            $begin = microtime(true);
            $this->collectClassChanges();
            $end = microtime(true);
            $this->log->log( 'Collected Class Changes: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectFiles();
            $end = microtime(true);
            $this->log->log( 'Collected Files: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;

            $this->collectFilesDependencies();
            $end = microtime(true);
            $this->log->log( 'Collected Files Dependencies: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->getAtomCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Atom Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;

            $this->collectPhpStructures();
            $end = microtime(true);
            $this->log->log( 'Collected Php Structures: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectStructures();
            $end = microtime(true);
            $this->log->log( 'Collected Structures: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectFunctions();
            $end = microtime(true);
            $this->log->log( 'Collected Functions: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectConstants();
            $end = microtime(true);
            $this->log->log( 'Collected Constants: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectVariables();
            $end = microtime(true);
            $this->log->log( 'Collected Variables: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectLiterals();
            $end = microtime(true);
            $this->log->log( 'Collected Literals: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectReadability();
            $end = microtime(true);
            $this->log->log( 'Collected Readability: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;

            $this->collectParameterCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Parameter Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectLocalVariableCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Local Variable Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectMethodsCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Method Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectPropertyCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Property Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectConstantCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Constant Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $begin = $end;
            $this->collectNativeCallsPerExpressions();
            $end = microtime(true);
            $this->log->log( 'Collected Native Calls Per Expression: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $this->collectClassTraitsCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Trait counts per Class: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $this->collectClassInterfaceCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Interface count per Class: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            $this->collectClassChildrenCounts();
            $end = microtime(true);
            $this->log->log( 'Collected Children count per Class: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = $end;
            $this->collectDefinitionsStats();
            $end = microtime(true);
            $this->log->log( 'Collected Definitions stats : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = $end;
            $this->collectClassDepth();
            $end = microtime(true);
            $this->log->log( 'Collected Classes stats : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = $end;
            $this->collectForeachFavorite();
            $end = microtime(true);
            $this->log->log( 'Collected Foreach favorites : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = microtime(true);
            $this->collectGlobalVariables();
            $end = microtime(true);
            $this->log->log( 'Collected Global Variables : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = microtime(true);
            $this->collectInclusions();
            $end = microtime(true);
            $this->log->log( 'Collected Inclusion relationship : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

            $begin = microtime(true);
            $this->collectMissingDefinitions();
            $end = microtime(true);
            $this->log->log( 'Collected Missing definitions : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
            
            $begin = microtime(true);
            $this->collectCyclomaticComplexity();
            $end = microtime(true);
            $this->log->log( 'Collected Cyclomatic Complexity : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        }

        $counts = array();
        $datastore = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READONLY);
        $datastore->busyTimeout(5000);
        $res = $datastore->query('SELECT * FROM analyzed');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = (int) $row['counts'];
        }
        $this->log->log('count analyzed : ' . count($counts) . "\n");
        $this->log->log('counts ' . implode(', ', $counts) . "\n");
        $datastore->close();
        unset($datastore);

        if (!empty($this->config->thema)) {
            $thema = $this->config->thema;
            $themes = $this->themes->getRulesetsAnalyzers($thema);
            if (empty($themes)) {
                $r = $this->themes->getSuggestionThema($thema);
                if (!empty($r)) {
                    echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
                }
                throw new NoSuchThema($thema);
            }
            display('Processing thema ' . (count($thema) > 1 ? 's' : '' ) . ' : ' . implode(', ', $thema));
            $missing = $this->processResultsRuleset($thema, $counts);
            $this->expandRulesets();
            $this->collectHashAnalyzer();
            
            if ($missing === 0) {
                $list = '(NULL, "' . implode('"), (NULL, "', $thema) . '")';
                $this->sqlite->query("INSERT INTO themas (\"id\", \"thema\") VALUES {$list}");
                $themes = array();
            }

        } elseif (!empty($this->config->program)) {
            $analyzer = $this->config->program;
            if(is_array($analyzer)) {
                $themes = $analyzer;
            } else {
                $themes = array($analyzer);
            }

            foreach($themes as $theme) {
                if (!$this->themes->getClass($theme)) {
                    throw new NoSuchAnalyzer($theme, $this->themes);
                }
            }
            display('Processing ' . count($themes) . ' analyzer' . (count($themes) > 1 ? 's' : '') . ' : ' . implode(', ', $themes));

            if (isset($counts[$analyzer])) {
                $this->processResults($analyzer, $counts[$analyzer]);
                $this->collectHashAnalyzer();
            } else {
                display("$analyzer is not run yet.");
            }

        } else {
            $themes = array();
        }

        $this->log->log('Still ' . count($themes) . " to be processed\n");
        display('Still ' . count($themes) . " to be processed\n");

        $this->finish();
    }
    
    public function finalMark($finalMark) {
        $sqlite = new \Sqlite3($this->config->dump);

        $values = array();
        foreach($finalMark as $key => $value) {
            $values[] = "(null, '$key', '$value')";
        }

        $sqlite->query('REPLACE INTO hash VALUES ' . implode(', ', $values));
    }

    private function processResultsRuleset($theme, array $counts = array()) {
        $analyzers = $this->themes->getRulesetsAnalyzers($theme);
        $classesList = makeList($analyzers);

        $this->sqlite->query("DELETE FROM results WHERE analyzer IN ($classesList)");
        
        $query = array();
        foreach($analyzers as $analyzer) {
            if (isset($counts[$analyzer])) {
                $query[] = "(NULL, '$analyzer', $counts[$analyzer])";
            }
        }

        if (!empty($query)) {
            $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES ' . implode(', ', $query));
        }

        $specials = array('Php/Incompilable',
                          'Composer/UseComposer',
                          'Composer/UseComposerLock',
                          'Composer/Autoload',
                          );
        $diff = array_intersect($specials, $analyzers);
        if (!empty($diff)) {
            foreach($diff as $d) {
                $this->processResults($d, $counts[$d] ?? -3);
            }
            $analyzers = array_diff($analyzers, $diff);
        }

        $linksDown = $this->linksDown;
//        $linksDown .= '. "DEFINITION"'

        $saved = 0;
        $docs = new Docs($this->config->dir_root, $this->config->ext, $this->config->dev);
        $severities = array();
        $readCounts = array_fill_keys($analyzers, 0);

        $chunks = array_chunk($analyzers, 200);
        // Gremlin only accepts chunks of 255 maximum

        foreach($chunks as $chunk) {
            $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", within(args))
.sideEffect{ analyzer = it.get().value("analyzer"); }
.out("ANALYZED")
.sideEffect{ line = it.get().value("line");
             fullcode = it.get().value("fullcode");
             file="None"; 
             theFunction = ""; 
             theClass=""; 
             theNamespace=""; 
             }
.where( __.until( hasLabel("Project") ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() in ["Function", "Closure", "Arrayfunction", "Magicmethod", "Method"]) { theFunction = it.get().value("fullcode")} }
      .sideEffect{ if (it.get().label() in ["Class", "Trait", "Interface", "Classanonymous"]) { theClass = it.get().value("fullcode")} }
      .sideEffect{ if (it.get().label() == "File") { file = it.get().value("fullcode")} }
       )
)
.map{ ["fullcode":fullcode, 
       "file":file, 
       "line":line, 
       "namespace":theNamespace, 
       "class":theClass, 
       "function":theFunction,
       "analyzer":analyzer];}

GREMLIN;
            $res = $this->gremlin->query($query, array('args' => $chunk))
                                 ->toArray();

            $query = array();
            foreach($res as $result) {
                if (empty($result)) {
                    continue;
                }
                
                if (isset($severities[$result['analyzer']])) {
                    $severity = $severities[$result['analyzer']];
                } else {
                    $severity = $this->sqlite->escapeString($docs->getDocs($result['analyzer'])['severity']);
                    $severities[$result['analyzer']] = $severity;
                }
    
                ++$readCounts[$result['analyzer']];
    
                $query[] = <<<SQL
(null, 
 '{$this->sqlite->escapeString($result['fullcode'])}', 
 '{$this->sqlite->escapeString($result['file'])}', 
  {$this->sqlite->escapeString($result['line'])}, 
 '{$this->sqlite->escapeString($result['namespace'])}', 
 '{$this->sqlite->escapeString($result['class'])}', 
 '{$this->sqlite->escapeString($result['function'])}',
 '{$this->sqlite->escapeString($result['analyzer'])}',
 '$severity'
)
SQL;
                ++$saved;
    
                // chunk split the save.
                if ($saved % 100 === 0) {
                    $values = implode(', ', $query);
                    $query = <<<SQL
REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES $values
SQL;
                    $this->sqlite->query($query);
                    $query = array();
                }
            }
        }
        
        if (!empty($query)) {
            $values = implode(', ', $query);
            $query = <<<SQL
REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES $values
SQL;
            $this->sqlite->query($query);
        }

        $this->log->log(implode(', ', $theme) . " : dumped $saved");

        $error = 0;
        foreach($analyzers as $class) {
            if (!isset($counts[$class]) || $counts[$class] < 0) {
                continue;
            }

            if ($counts[$class] === $readCounts[$class]) {
                display("All $counts[$class] results saved for $class\n");
            } else {
                assert($counts[$class] === $readCounts[$class], "'results were not correctly dumped in $class : $readCounts[$class]/$counts[$class]");
                $error++;
                display("$readCounts[$class] results saved, $counts[$class] expected for $class\n");
            }
        }

        return $error;
    }

    private function processResults($class, int $count) {
        $this->sqlite->query("DELETE FROM results WHERE analyzer = '$class'");

        $this->sqlite->query("REPLACE INTO resultsCounts (\"id\", \"analyzer\", \"count\") VALUES (NULL, '$class', $count)");

        $this->log->log( "$class : $count\n");
        // No need to go further
        if ($count <= 0) {
            return;
        }

        $analyzer = $this->themes->getInstance($class, $this->gremlin, $this->config);
        $res = $analyzer->getDump();

        $saved = 0;
        $docs = new Docs($this->config->dir_root, $this->config->ext, $this->config->dev);
        $severity = $docs->getDocs($class)['severity'];

        $query = array();
        foreach($res as $result) {
            if (empty($result)) {
                continue;
            }
            
            $query[] = <<<SQL
(null, 
 '{$this->sqlite->escapeString($result['fullcode'])}', 
 '{$this->sqlite->escapeString($result['file'])}', 
  {$this->sqlite->escapeString($result['line'])}, 
 '{$this->sqlite->escapeString($result['namespace'])}', 
 '{$this->sqlite->escapeString($result['class'])}', 
 '{$this->sqlite->escapeString($result['function'])}',
 '{$this->sqlite->escapeString($class)}',
 '{$this->sqlite->escapeString($severity)}'
)
SQL;
            ++$saved;

            // chunk split the save.
            if ($saved % 500 === 0) {
                $values = implode(', ', $query);
                $query = <<<SQL
REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES $values
SQL;
                $this->sqlite->query($query);
                $query = array();
            }
        }
        
        if (!empty($query)) {
            $values = implode(', ', $query);
            $query = <<<SQL
REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") 
             VALUES $values
SQL;
            $this->sqlite->query($query);
        }

        $this->log->log("$class : dumped $saved");

        if ($count === $saved) {
            display("All $saved results saved for $class\n");
        } else {
            assert($count === $saved, "'results were not correctly dumped in $class : $saved/$count");
            display("$saved results saved, $count expected for $class\n");
        }

    }

    private function getAtomCounts() {
        $this->sqlite->query('DROP TABLE IF EXISTS atomsCounts');
        $this->sqlite->query('CREATE TABLE atomsCounts (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                          atom STRING,
                                                          count INTEGER
                                              )');

        $query = 'g.V().groupCount("b").by(label).cap("b").next();';
        $atomsCount = $this->gremlin->query($query);

        $counts = array();
        foreach($atomsCount as $c) {
            foreach($c as $atom => $count) {
                $counts[] = "(null, '$atom', $count)";
            }
        }
        
        $query = 'INSERT INTO atomsCounts ("id", "atom", "count") VALUES ' . implode(', ', $counts);
        $this->sqlite->query($query);
        
        display(count($atomsCount) . " atoms\n");
    }

    private function finish() {
        $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, \'Project/Dump\', 1)');

        /*
        // This is way too slow and costly, just for stats.
        // Redo each time so we update the final counts
        $totalNodes = $this->gremlin->query('g.V().count()')->toString();
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total nodes", '.$totalNodes.')');

        $totalEdges = $this->gremlin->query('g.E().count()')->toString();
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total edges", '.$totalEdges.')');

        $totalProperties = $this->gremlin->query('g.V().properties().count()')->toString();
        $this->sqlite->query('REPLACE INTO hash VALUES(null, "total properties", '.$totalProperties.')');
        */
        rename($this->sqliteFile, $this->sqliteFileFinal);

        $this->removeSnitch();
    }

    private function collectDatastore() {
        $tables = array('analyzed',
                        'compilation52',
                        'compilation53',
                        'compilation54',
                        'compilation55',
                        'compilation56',
                        'compilation70',
                        'compilation71',
                        'compilation72',
                        'compilation73',
                        'compilation74',
                        'compilation80',
                        'composer',
                        'configFiles',
                        'externallibraries',
                        'files',
                        'hash',
                        'ignoredFiles',
                        'shortopentag',
                        'tokenCounts',
                        'linediff',
                        );
        $this->collectTables($tables);
    }

    private function collectTables($tables) {
        $this->sqlite->query("ATTACH '{$this->config->datastore}' AS datastore");

        $query = "SELECT name, sql FROM datastore.sqlite_master WHERE type='table' AND name in ('" . implode("', '", $tables) . "');";
        $existingTables = $this->sqlite->query($query);

        while($table = $existingTables->fetchArray(\SQLITE3_ASSOC)) {
            $createTable = $table['sql'];
            $createTable = str_replace('CREATE TABLE ', 'CREATE TABLE IF NOT EXISTS ', $createTable);

            $this->sqlite->query($createTable);
            $this->sqlite->query('REPLACE INTO ' . $table['name'] . ' SELECT * FROM datastore.' . $table['name']);
        }

        $this->sqlite->query('DETACH datastore');
    }

    private function collectTablesData($tables) {
        $this->sqlite->query("ATTACH '{$this->config->datastore}' AS datastore");

        $query = "SELECT name, sql FROM datastore.sqlite_master WHERE type='table' AND name in ('" . implode("', '", $tables) . "');";
        $existingTables = $this->sqlite->query($query);

        while($table = $existingTables->fetchArray(\SQLITE3_ASSOC)) {
            $this->sqlite->query('REPLACE INTO ' . $table['name'] . ' SELECT * FROM datastore.' . $table['name']);
        }

        $this->sqlite->query('DETACH datastore');
    }

    private function collectHashAnalyzer() {
        $tables = array('hashAnalyzer',
                       );
        $this->collectTablesData($tables);
    }

    private function collectVariables() {
        // Name spaces
        $this->sqlite->query('DROP TABLE IF EXISTS variables');
        $this->sqlite->query('CREATE TABLE variables (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                        variable STRING,
                                                        type STRING
                                                 )');

        $query = <<<'GREMLIN'
g.V().hasLabel("Variable", "Variablearray", "Variableobject").has("token", "T_VARIABLE")
                                                             .map{ ['name' : it.get().value("fullcode"), 
                                                                    'type' : it.get().label()        ] };
GREMLIN;
        $variables = $this->gremlin->query($query);

        $total = 0;
        $query = array();

        $types = array('Variable'       => 'var',
                       'Variablearray'  => 'array',
                       'Variableobject' => 'object',
                      );
        $unique = array();
        foreach($variables as $row) {
            if (isset($unique[$row['name'] . $row['type']])) {
                continue;
            }
            $name = str_replace(array('&', '...'), '', $row['name']);
            $unique[$name . $row['type']] = 1;
            $type = $types[$row['type']];
            $query[] = "(null, '" . strtolower($this->sqlite->escapeString($name)) . "', '" . $type . "')";
            ++$total;
        }
        
        if (!empty($query)) {
            $query = 'INSERT INTO variables ("id", "variable", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        
        display( "Variables : $total\n");
    }
    
    private function collectStructures() {
        // Name spaces
        $this->sqlite->query('DROP TABLE IF EXISTS namespaces');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE namespaces (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           namespace STRING
                        )
SQL
);
        $this->sqlite->query('INSERT INTO namespaces VALUES ( 1, "")');

        $query = <<<'GREMLIN'
g.V().hasLabel("Namespace").out("NAME").map{ ['name' : it.get().value("fullcode")] }.unique();
GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '\\" . strtolower($this->sqlite->escapeString($row['name'])) . "')";
            ++$total;
        }
        
        if (!empty($query)) {
            $query = 'INSERT INTO namespaces ("id", "namespace") VALUES ' . implode(', ', $query);
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
                                                   begin INTEGER,
                                                   end INTEGER,
                                                   file INTEGER,
                                                   namespaceId INTEGER DEFAULT 1
                                                 )');

        $this->sqlite->query('DROP TABLE IF EXISTS cit_implements');
        $this->sqlite->query('CREATE TABLE cit_implements (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                             implementing INTEGER,
                                                             implements INTEGER,
                                                             type    TEXT
                                                 )');

        $MAX_LOOPING = Analyzer::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().hasLabel("Class")
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").sideEffect{ implementList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Usetrait").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "USE", "PPP", "CONST").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = '';}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'abstract':it.get().properties("abstract").any(),
         'final':it.get().properties("final").any(),
         'extends':extendList,
         'implements':implementList,
         'uses':useList,
         'type':'class',
         'begin':lines.min(),
         'end':lines.max(),
         'file':file
         ];
}

GREMLIN;
        $classes = $this->gremlin->query($query);

        $total = 0;
        $usesId = array();
        
        $cit = array();
        $citId = array();
        $citCount = $this->sqlite->querySingle('SELECT count(*) FROM cit');

        foreach($classes as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $cit[] = $row;
            $citId[$row['fullnspath']] = ++$citCount;
            
            ++$total;
        }

        display("$total classes\n");

        // Interfaces
        $query = <<<GREMLIN
g.V().hasLabel("Interface")
.sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "CONST").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = [];}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'extends':extendList,
         'type':'interface',
         'abstract':0,
         'final':0,
         'begin':lines.min(),
         'end':lines.max(),
         'file':file
         ];
}
GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $cit[] = $row;
            $citId[$row['fullnspath']] = ++$citCount;

            ++$total;
        }

        display("$total interfaces\n");

        // Traits
        $query = <<<GREMLIN
g.V().hasLabel("Trait")
.sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Usetrait").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "USE", "PPP").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = '';}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.map{ 
        ['fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'uses':useList,
         'type':'trait',
         'abstract':0,
         'final':0,
         'begin':lines.min(),
         'end':lines.max(),
         'file':file
         ];
}

GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        foreach($res as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }
            
            $row['implements'] = array(); // always empty
            $cit[] = $row;
            $citId[$row['fullnspath']] = ++$citCount;

            ++$total;
        }
        
        display("$total traits\n");
        
        if (!empty($cit)) {
            $query = array();
            
            foreach($cit as $row) {
                if (empty($row['extends'])) {
                    $extends = "''";
                } elseif (isset($citId[$row['extends']])) {
                    $extends = $citId[$row['extends']];
                } else {
                    $extends = "'{$this->sqlite->escapeString($row['extends'])}'";
                }

                $namespace = preg_replace('/\\\\[^\\\\]*?$/', '', $row['fullnspath']);
                if (isset($namespacesId[$namespace])) {
                    $namespaceId = $namespacesId[$namespace];
                } else {
                    $namespaceId = 1;
                }

                $query[] = '(' . $citId[$row['fullnspath']] .
                           ", '" . $this->sqlite->escapeString($row['name']) . "'" .
                           ', ' . $namespaceId .
                           ', ' . (int) $row['abstract'] .
                           ',' . (int) $row['final'] .
                           ", '" . $row['type'] . "'" .
                           ', ' . $extends .
                           ', ' . (int) $row['begin'] .
                           ', ' . (int) $row['end'] .
                           ", '" . $this->files[$row['file']] . "'" .
                           ' )';
            }
            
            if (!empty($query)) {
                $query = 'INSERT OR IGNORE INTO cit ("id", "name", "namespaceId", "abstract", "final", "type", "extends", "begin", "end", "file") VALUES ' . implode(", \n", $query);
                $this->sqlite->query($query);
            }

            $query = array();
            foreach($cit as $row) {
                if (empty($row['implements'])) {
                    continue;
                }

                foreach($row['implements'] as $implements) {
                    if (isset($citId[$implements])) {
                        $query[] = '(null, ' . $citId[$row['fullnspath']] . ", $citId[$implements], 'implements')";
                    } else {
                        $query[] = '(null, ' . $citId[$row['fullnspath']] . ", '" . $this->sqlite->escapeString($implements) . "', 'implements')";
                    }
                }
            }

            if (!empty($query)) {
                $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES ' . implode(', ', $query);
                $this->sqlite->query($query);
            }

            $query = array();
            foreach($cit as $row) {
                if (empty($row['uses'])) {
                    continue;
                }
                
                foreach($row['uses'] as $uses) {
                    if (isset($citId[$uses])) {
                        $query[] = '(null, ' . $citId[$row['fullnspath']] . ", $citId[$uses], 'use')";
                    } else {
                        $query[] = '(null, ' . $citId[$row['fullnspath']] . ", '" . $this->sqlite->escapeString($uses) . "', 'use')";
                    }
                }
            }

            if (!empty($query)) {
                $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES ' . implode(', ', $query);
                $this->sqlite->query($query);
            }
        }

        // Manage use (traits)
        // Same SQL than for implements

        $total = 0;
        $query = array();
        foreach($usesId as $id => $usesFNP) {
            foreach($usesFNP as $fnp) {
                if (substr($fnp, 0, 2) === '\\\\') {
                    $fnp = substr($fnp, 2);
                }
                if (isset($citId[$fnp])) {
                    $query[] = "(null, $id, $citId[$fnp], 'use')";

                    ++$total;
                } // Else ignore. Not in the project
            }
        }
        if (!empty($query)) {
            $query = 'INSERT INTO cit_implements ("id", "implementing", "implements", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display("$total uses \n");

        // Methods
        $this->sqlite->query('DROP TABLE IF EXISTS methods');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE methods (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                        method INTEGER,
                        citId INTEGER,
                        static INTEGER,
                        final INTEGER,
                        abstract INTEGER,
                        visibility STRING,
                        begin INTEGER,
                        end INTEGER
                     )
SQL
);

        
        $query = <<<GREMLIN
g.V().hasLabel("Method", "Usetrait")
    .coalesce( 
            __.out("BLOCK").out("EXPRESSION").hasLabel("As"),
            __.hasLabel("Method", "Magicmethod")
     )
     .where(
        __.coalesce( 
            __.out("AS").sideEffect{alias = it.get().value("fullcode")}.in("AS")
              .out("NAME").in("DEFINITION").hasLabel("Method", "Magicmethod"), 
            __.sideEffect{ alias = false; }
          )
         .as("method")
         .in("METHOD", "MAGICMETHOD").hasLabel("Class", "Interface", "Trait").sideEffect{classe = it.get().value("fullnspath"); }
         .select("method")
         .where( __.sideEffect{ lines = [];}
                   .out("BLOCK").out("EXPRESSION")
                   .emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING)
                   .sideEffect{ lines.add(it.get().value("line")); }
                   .fold()
          )
          .map{ 

    if (alias == false) {
        name = it.get().value("fullcode");
    } else {
        name = it.get().value("fullcode").replaceFirst("function .*?\\\\(", "function "+alias+"(" );
    }

    x = ["name": name,
         "abstract":it.get().properties("abstract").any(),
         "final":it.get().properties("final").any(),
         "static":it.get().properties("static").any(),

         "public":    it.get().value("visibility") == "public",
         "protected": it.get().value("visibility") == "protected",
         "private":   it.get().value("visibility") == "private",
         "class":     classe,
         "begin":     lines.min(),
         "end":       lines.max()
         ];
            }
      ) 
.map{x;}

GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        $query = array();
        foreach($res as $row) {
            if ($row['public']) {
                $visibility = 'public';
            } elseif ($row['protected']) {
                $visibility = 'protected';
            } elseif ($row['private']) {
                $visibility = 'private';
            } else {
                $visibility = '';
            }

            if (!isset($citId[$row['class']])) {
                continue;
            }
            $query[] = "(null, '" . $this->sqlite->escapeString($row['name']) . "', " . $citId[$row['class']] .
                        ', ' . (int) $row['static'] . ', ' . (int) $row['final'] . ', ' . (int) $row['abstract'] . ", '" . $visibility . "'" .
                        ', ' . (int) $row['begin'] . ', ' . (int) $row['end'] . ')';

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO methods ("id", "method", "citId", "static", "final", "abstract", "visibility", "begin", "end") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }

        display("$total methods\n");

        // Properties
        $this->sqlite->query('DROP TABLE IF EXISTS properties');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE properties (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           property INTEGER,
                           citId INTEGER,
                           visibility STRING,
                           static INTEGER,
                           value TEXT
                           )
SQL
);

        $query = <<<'GREMLIN'
g.V().hasLabel("Propertydefinition").as("property")
     .not(has('virtual', true))
     .in("PPP")
.sideEffect{ 
    x_static = it.get().properties("static").any();
    x_public = it.get().value("visibility") == "public";
    x_protected = it.get().value("visibility") == "protected";
    x_private = it.get().value("visibility") == "private";
    x_var = it.get().value("token") == "T_VAR";
}
     .in("PPP").hasLabel("Class", "Interface", "Trait")
     .sideEffect{classe = it.get().value("fullnspath"); }
     .select("property")
.map{ 
    name = it.get().value("fullcode");
    if (it.get().vertices(OUT, "DEFAULT").any()) { 
        v = it.get().vertices(OUT, "DEFAULT").next().value("fullcode");
    } else { 
        v = ""; 
    }

    x = ["class":classe,
         "static":x_static,
         "public":x_public,
         "protected":x_protected,
         "private":x_private,
         "var":x_var,
         "name": name,
         "value": v];
}

GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        $query = array();
        foreach($res as $row) {
            if ($row['public']) {
                $visibility = 'public';
            } elseif ($row['protected']) {
                $visibility = 'protected';
            } elseif ($row['private']) {
                $visibility = 'private';
            } elseif ($row['var']) {
                $visibility = '';
            } else {
                continue;
            }

            // If we haven't found any definition for this class, just ignore it.
            if (!isset($citId[$row['class']])) {
                continue;
            }

            $query[] = "(null, '" . $this->sqlite->escapeString($row['name']) . "', " . $citId[$row['class']] .
                        ", '" . $visibility . "', '" . $this->sqlite->escapeString($row['value']) . "', " . (int) $row['static'] . ')';

            ++$total;
        }
        if (!empty($query)) {
            $query = 'INSERT INTO properties ("id", "property", "citId", "visibility", "value", "static") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        
        display("$total properties\n");

        // Class Constant
        $this->sqlite->query('DROP TABLE IF EXISTS constants');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE constants (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          constant INTEGER,
                          citId INTEGER,
                          visibility STRING,
                          value TEXT
                       )
SQL
);

        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous", "Trait")
     .out('CONST')
.sideEffect{ 
    x_public = it.get().values("visibility") == 'public';
    x_protected = it.get().values("visibility") == 'protected';
    x_private = it.get().values("visibility") == 'private';
}
     .out('CONST')
     .map{ 
    x = ['name': it.get().vertices(OUT, 'NAME').next().value("fullcode"),
         'value': it.get().vertices(OUT, 'VALUE').next().value("fullcode"),
         "public":x_public,
         "protected":x_protected,
         "private":x_private,
         'class': it.get().vertices(IN, 'CONST').next().vertices(IN, 'CONST').next().value("fullnspath")
         ];
}

GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        $query = array();
        foreach($res as $row) {
            if ($row['public']) {
                $visibility = 'public';
            } elseif ($row['protected']) {
                $visibility = 'protected';
            } elseif ($row['private']) {
                $visibility = 'private';
            } else {
                $visibility = '';
            }

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }

            $query[] = "(null, '" . $this->sqlite->escapeString($row['name']) . "'" .
                       ', ' . $citId[$row['class']] .
                       ", '" . $visibility . "'" .
                       ", '" . $this->sqlite->escapeString($row['value']) . "')";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO constants ("id", "constant", "citId", "visibility", "value") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display("$total constants\n");
    }

    private function collectFiles() {
        $res = $this->sqlite->query('SELECT * FROM files');
        $this->files = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $this->files[$row['file']] = $row['id'];
        }
    }

    private function collectPhpStructures() {
        $this->sqlite->query('DROP TABLE IF EXISTS phpStructures');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE phpStructures (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                              name TEXT,
                              type TEXT,
                              count INTEGER
)
SQL
);
        
        $this->collectPhpStructures2('Functioncall', 'Functions/IsExtFunction', 'function');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Constants/IsExtConstant', 'constant');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Interfaces/IsExtInterface', 'interface');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Traits/IsExtTrait', 'trait');
        $this->collectPhpStructures2('Newcall", "Identifier", "Nsname', 'Classes/IsExtClass', 'class');
    }
    
    private function collectPhpStructures2($label, $analyzer, $type) {
        $query = <<<GREMLIN
g.V().hasLabel("$label").where( __.in("ANALYZED").has("analyzer", "$analyzer"))
.coalesce( __.out("NAME"), __.filter{true;})
.groupCount("m").by("fullcode").cap("m").next().sort{ it.value.toInteger() };
GREMLIN;
        $res = $this->gremlin->query($query);

        $total = 0;
        $query = array();
        foreach($res as $row) {
            $count = current($row);
            $name = key($row);
            $query[] = "(null, '" . $this->sqlite->escapeString($name) . "', '$type', $count)";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO phpStructures ("id", "name", "type", "count") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display("$total PHP {$type}s\n");
    }
    
    private function collectConstants() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Defineconstant")
     .where( __.out("ARGUMENT").has("rank", 0).has("constant", true).sideEffect{ name = it.get().value("fullcode"); } )
     .where( __.out("ARGUMENT").has("rank", 1).has("constant", true).sideEffect{ v = it.get().value("fullcode"); } )
     .filter{ v != null;}
     .filter{ name != null;}
.map{ 
    x = ['name': name,
         'value': v
         ];
}

GREMLIN;
        $res = $this->gremlin
                    ->query($query)
                    ->toArray();
        
        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '" . $this->sqlite->escapeString(trim($row['name'], "'\"")) . "', 0, 0, '" . $this->sqlite->escapeString($row['value']) . "')";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO constants ("id", "constant", "citId", "visibility", "value") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }

        $gremlinQuery = <<<'GREMLIN'
g.V().hasLabel("Const")
     .not(where(__.in("CONST")))
     .out("CONST")
     .hasLabel("Constant")
     .where( __.out("NAME").sideEffect{ name = it.get().value("fullcode"); } )
     .where( __.out("VALUE").sideEffect{ v = it.get().value("fullcode"); } )
.map{ 
    x = ['name': name,
         'value': v
         ];
}

GREMLIN;
        $res = $this->gremlin
                    ->query($gremlinQuery)
                    ->toArray();
        
        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '" . $this->sqlite->escapeString($row['name']) . "', 0, 0, '" . $this->sqlite->escapeString($row['value']) . "')";

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO constants ("id", "constant", "citId", "visibility", "value") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }

        display("$total constants\n");
    }

    private function collectFunctions() {
        $MAX_LOOPING = Analyzer::MAX_LOOPING;

        // Functions
        $this->sqlite->query('DROP TABLE IF EXISTS functions');
        $this->sqlite->query(<<<'SQL'
CREATE TABLE functions (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          function TEXT,
                          file TEXT,
                          begin INTEGER,
                          end INTEGER
)
SQL
);

        $query = <<<GREMLIN
g.V().hasLabel("Function")
.sideEffect{ lines = [];}.where( __.out("BLOCK").out("EXPRESSION").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold() )
.sideEffect{ file = '';}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.map{ 
    x = ['name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'file': file,
         'begin': lines.min(),
         'end': lines.max()
         ];
}

GREMLIN;
        $res = $this->gremlin->query($query)->toArray();
        
        $total = 0;
        $query = array();
        foreach($res as $row) {
            $query[] = "(null, '" . $this->sqlite->escapeString($row['name']) . "', '" . $this->files[$row['file']] . "', " . (int) $row['begin'] . ', ' . (int) $row['end'] . ')';

            ++$total;
        }

        if (!empty($query)) {
            $query = 'INSERT INTO functions ("id", "function", "file", "begin", "end") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }

        display("$total functions\n");
    }

    private function collectLiterals() {
        $types = array('Integer', 'Float', 'String', 'Heredoc', 'Arrayliteral');

        foreach($types as $type) {
            $this->sqlite->query('DROP TABLE IF EXISTS literal' . $type);
            $this->sqlite->query('CREATE TABLE literal' . $type . ' (  
                                                   id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                   name STRING,
                                                   file STRING,
                                                   line INTEGER
                                                 )');

            $filter = 'hasLabel("' . $type . '")';

            $b = microtime(true);
            $query = <<<GREMLIN

g.V().$filter
     .has('constant', true)
     .sideEffect{ name = it.get().value("fullcode");
                  line = it.get().value('line');
                  file='None'; 
      }
     .until( hasLabel('File') )
     .repeat( __.inE().hasLabel({$this->linksDown}).outV() 
                .sideEffect{ if (it.get().label() == 'File') { file = it.get().value('fullcode')} }
     )
     .map{ 
        x = ['name': name,
             'file': file,
             'line': line
             ];
     }

GREMLIN;
            $res = $this->gremlin->query($query);
    
            $total = 0;
            $query = array();
            foreach($res as $value => $row) {
                $query[] = "('" . $this->sqlite->escapeString($row['name']) . "','" . $this->sqlite->escapeString($row['file']) . "'," . $row['line'] . ')';
                ++$total;
                if ($total % 10000 === 0) {
                    $query = "INSERT INTO literal$type (name, file, line) VALUES " . implode(', ', $query);
                    $this->sqlite->query($query);
                    $query = array();
                }
            }
            
            if (!empty($query)) {
                $query = "INSERT INTO literal$type (name, file, line) VALUES " . implode(', ', $query);
                $this->sqlite->query($query);
            }
            
            $query = "INSERT INTO resultsCounts (analyzer, count) VALUES (\"$type\", $total)";
            $this->sqlite->query($query);
            display( "literal$type : $total\n");
        }

       $otherTypes = array('Null', 'Boolean', 'Closure');
       foreach($otherTypes as $type) {
            $query = <<<GREMLIN
g.V().hasLabel("$type").count();
GREMLIN;
            $total = $this->gremlin->query($query)->toInt();

            $query = "INSERT INTO resultsCounts (analyzer, count) VALUES (\"$type\", $total)";
            $this->sqlite->query($query);
            display( "Other $type : $total\n");
       }

       $this->sqlite->query('DROP TABLE IF EXISTS stringEncodings');
       $this->sqlite->query('CREATE TABLE stringEncodings (  
                                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                                              encoding STRING,
                                              block STRING,
                                              CONSTRAINT "encoding" UNIQUE (encoding, block)
                                            )');

        $query = <<<'GREMLIN'
g.V().hasLabel('String').map{ x = ['encoding':it.get().values('encoding')[0]];
    if (it.get().values('block').size() != 0) {
        x['block'] = it.get().values('block')[0];
    }
    x;
}

GREMLIN;
        $res = $this->gremlin->query($query);
        
        $query = array();
        foreach($res as $row) {
            if (isset($row['block'])){
                $query[] = '(\'' . $row['encoding'] . '\', \'' . $row['block'] . '\')';
            } else {
                $query[] = '(\'' . $row['encoding'] . '\', \'\')';
            }
        }
       
       if (!empty($query)) {
           $query = 'REPLACE INTO stringEncodings ("encoding", "block") VALUES ' . implode(', ', $query);
           $this->sqlite->query($query);
       }
    }

    private function collectDefinitionsStats() {
        $insert = array();
        $types = array('Staticconstant'   => 'staticconstants',
                       'Staticmethodcall' => 'staticmethodcalls',
                       'Staticproperty'   => 'staticproperty',

                       'Methodcall'       => 'methodcalls',
                       'Member'           => 'members',
                        );
        
        foreach($types as $label => $name) {
            $query = <<<GREMLIN
g.V().hasLabel("$label").count();
GREMLIN;
            $res = $this->gremlin->query($query);
            $insert[] = '("' . $name . '", ' . $res->toInt() . ')';

            $query = <<<GREMLIN
g.V().hasLabel("$label").where(__.in("DEFINITION").not(hasLabel("Virtualproperty"))).count();
GREMLIN;
            $res = $this->gremlin->query($query);
            $insert[] = '("' . $name . ' defined", ' . $res->toInt() . ')';
        }

        $this->sqlite->query('REPLACE INTO hash ("key", "value") VALUES ' . implode(', ', $insert));
        display('Definitions Stats');
    }

    private function collectFilesDependencies() {
        $this->sqlite->query('DROP TABLE IF EXISTS filesDependencies');
        $this->sqlite->query('CREATE TABLE filesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                               including STRING,
                                                               included STRING,
                                                               type STRING
                                                 )');

        // Direct inclusion
        $query = $this->newQuery('Inclusions');
        $query->atomIs('Include', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('ARGUMENT')
              ->outIsIE('CODE')
              ->_as('include')
              ->goToInstruction('File')
              ->_as('file')
              ->select(array('file'    => 'fullcode',
                             'include' => 'fullcode'));
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $query = array();
        if (count($result) > 0) {
            foreach($result->toArray() as $link) {
                $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'INCLUDE')";
            }

            if (!empty($query)) {
                $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
                $this->sqlite->query($query);
            }
            display(count($result) . ' inclusions');
        }

        // Finding extends and implements
        $query = $this->newQuery('Extensions');
        $query->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToInstruction('File')
              ->savePropertyAs('fullcode', 'calling')
              ->back('first')

              ->outIs(array('EXTENDS', 'IMPLEMENTS'))
              ->raw('outE().hasLabel("EXTENDS", "IMPLEMENTS").sideEffect{ type = it.get().label(); }.inV()', array(), array())
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)

              ->goToInstruction('File')
              ->savePropertyAs('fullcode', 'called')

              ->raw('map{ ["file":calling, "type":type, "include":called]; }', array(), array());
        $query->prepareRawQuery();
        $extends = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $query = array();
        foreach($extends->toArray() as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', '" . $link['type'] . "')";
        }

        if (!empty($query)) {
            $sqlQuery = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($sqlQuery);
        }
        display($extends->toInt() . ' extends for classes ');

        // Finding extends for interfaces
        $query = <<<'GREMLIN'
g.V().hasLabel("Interface").as("classe")
     .repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File")).as("file")
     .select("classe").out("EXTENDS")
     .repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File")).as("include")
     .select("file", "include").by("fullcode").by("fullcode")
GREMLIN;
        $res = $this->gremlin->query($query);
        $query = array();

        foreach($res as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'EXTENDS')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($res) . ' extends for interfaces ');

        // Finding typehint
        $query = <<<GREMLIN
g.V().hasLabel("Nsname", "Identifier").as("classe").where( __.in("TYPEHINT"))
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("file")
     .select("classe").in("DEFINITION")
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("include")
     .select("file", "include").by("fullcode").by("fullcode")
GREMLIN;
        $res = $this->gremlin->query($query);
        $query = array();

        foreach($res as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'TYPEHINT')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($res) . ' typehints ');

        // Finding trait use
        $query = <<<GREMLIN
g.V().hasLabel("Usetrait").out("USE").as("classe")
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("file")
     .select("classe").in("DEFINITION")
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("include")
     .select("file", "include").by("fullcode").by("fullcode")
GREMLIN;
        $res = $this->gremlin->query($query);
        $query = array();

        foreach($res as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'USE')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($res) . ' traits ');

        // traits
        $query = <<<GREMLIN
g.V().hasLabel("Class", "Trait").as("classe")
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("file")
     .select("classe").out("USE").hasLabel("Usetrait").out("USE").in("DEFINITION")
     .repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).as("include")
     .select("file", "include").by("fullcode").by("fullcode")
GREMLIN;
        $res = $this->gremlin->query($query);
        $query = array();

        foreach($res as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'USE')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($res) . ' use ');

        // Functioncall()
        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .where(__.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ calling = it.get().value("fullcode"); })
     .in("DEFINITION")
     .where(__.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ called = it.get().value("fullcode"); })
     .map{['file':calling, 'include':called]}
GREMLIN;
        $functioncall = $this->gremlin->query($query);
        $query = array();

        foreach($functioncall as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'FUNCTIONCALL')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($functioncall) . ' functioncall ');

        // constants
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").not(where( __.in("NAME", "CLASS", "MEMBER", "AS", "CONSTANT", "TYPEHINT", "EXTENDS", "USE", "IMPLEMENTS", "INDEX" ) ) )
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ calling = it.get().value('fullcode'); })
     .in("DEFINITION")
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ called = it.get().value('fullcode'); })
     .map{ [ 'file':calling, 'include':called];}
GREMLIN;
        $constants = $this->gremlin->query($query);
        $query = array();

        foreach($constants as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'CONSTANT')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($constants) . ' constants ');

        // New
        $query = <<<GREMLIN
g.V().hasLabel("New").out("NEW").as("i")
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ calling = it.get().value("fullcode"); })
     .in("DEFINITION")
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ called = it.get().value("fullcode"); })
     .map{ [ "file":calling, "include":called];}
GREMLIN;
        $res = $this->gremlin->query($query);
        $query = array();

        foreach($res as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', 'NEW')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($res) . ' new ');

        // static calls (property, constant, method)
        $query = <<<GREMLIN
g.V().hasLabel("Staticconstant", "Staticmethodcall", "Staticproperty").as("i")
     .sideEffect{ type = it.get().label().toLowerCase(); }
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ calling = it.get().value('fullcode'); })
     .out("CLASS").in("DEFINITION")
     .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ called = it.get().value('fullcode'); })
     .map{ [ 'file':calling, 'type':type, 'include':called];}
GREMLIN;
        $statics = $this->gremlin->query($query);
        $query = array();

        foreach($statics as $link) {
            $query[] = "(null, '" . $this->sqlite->escapeString($link['file']) . "', '" . $this->sqlite->escapeString($link['include']) . "', '" . strtoupper($link['type']) . "')";
        }

        if (!empty($query)) {
            $query = 'INSERT INTO filesDependencies ("id", "including", "included", "type") VALUES ' . implode(', ', $query);
            $this->sqlite->query($query);
        }
        display(count($statics) . ' static calls CPM');
    }

    private function collectHashCounts($query, $name) {
        $index = $this->gremlin->query($query);
        
        $values = array();
        foreach($index->toArray()[0] as $number => $count) {
            $values[] = "('$name', $number, $count) ";
        }
        
        if (!empty($values)) {
            $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $values);
            $this->sqlite->query($query);
        }

        display( "$name : " . count($values));
    }
    
    private function collectMissingDefinitions() {
        $values = array();

        $functioncallCount  = $this->gremlin->query('g.V().hasLabel("Functioncall").count()')[0];
        $functioncallMissed = $this->gremlin->query('g.V().hasLabel("Functioncall")
             .has("token", within("T_STRING", "T_NS_SEPARATOR"))
             .not(where(__.in("DEFINITION")))
             .not(where(__.in("ANALYZED").has("analyzer", "Functions/IsExtFunction")))
             .where( __.out("NAME").hasLabel("Identifier", "Nsname", "Name"))
        ');
        if ($functioncallMissed !== 0) {
            file_put_contents("{$this->config->log_dir}/functions.missing.txt", implode(PHP_EOL, array_column($functioncallMissed->toArray(), 'fullcode')));
            $functioncallMissed = $functioncallMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/functions.missing.txt", 'Nothing found');
            $functioncallMissed = 0;
        }
        $values[] = "('functioncall total', '$functioncallCount')";
        $values[] = "('functioncall missed', '$functioncallMissed')";

        $methodCount  = $this->gremlin->query('g.V().hasLabel("Methodcall").count()')[0];
        $methodMissed = $this->gremlin->query('g.V().hasLabel("Methodcall")
             .not(where(__.in("DEFINITION")))
             .where( __.out("METHOD").has("token", "T_STRING"))
        ');
        if (is_array($methodMissed)) {
            file_put_contents("{$this->config->log_dir}/methodcall.missing.txt", implode(PHP_EOL, array_column($methodMissed->toArray(), 'fullcode')));
            $methodMissed = $methodMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/methodcall.missing.txt", 'Nothing found');
            $methodMissed = 0;
        }
        $values[] = "('methodcall total', '$methodCount')";
        $values[] = "('methodcall missed', '$methodMissed')";
        
        $memberCount  = $this->gremlin->query('g.V().hasLabel("Member").count()')[0];
        $memberMissed = $this->gremlin->query('g.V().hasLabel("Member")
             .not(where(__.in("DEFINITION")))
             .where( __.out("MEMBER").has("token", "T_STRING"))
        ');
        if (is_array($memberMissed)) {
            file_put_contents("{$this->config->log_dir}/members.missing.txt", implode(PHP_EOL, array_column($memberMissed->toArray(), 'fullcode')));
            $memberMissed = $memberMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/members.missing.txt", 'Nothing found');
            $memberMissed = 0;
        }
        $values[] = "('member total', '$memberCount')";
        $values[] = "('member missed', '$memberMissed')";

        $staticMethodCount  = $this->gremlin->query('g.V().hasLabel("Staticmethodcall").count()')[0];
        $staticMethodMissed = $this->gremlin->query('g.V().hasLabel("Staticmethodcall")
             .not(where(__.in("DEFINITION")))
             .where( __.out("CLASS").hasLabel("Identifier", "Nsname", "Self", "Parent", "Static"))
             .not(where( __.out("CLASS").in("ANALYZED").has("analyzer", "Classes/IsExtClass")))
             .where( __.out("METHOD").has("token", "T_STRING"))
        ');
        if (is_array($staticMethodMissed)) {
            file_put_contents("{$this->config->log_dir}/staticmethodcall.missing.txt", implode(PHP_EOL, array_column($staticMethodMissed->toArray(), 'fullcode')));
            $staticMethodMissed = $staticMethodMissed->count();
        } else {
            file_put_contents("{$this->config->log_dir}/staticmethodcall.missing.txt", 'Nothing found');
            $staticMethodMissed = 0;
        }
        $values[] = "('static methodcall total', '$staticMethodCount')";
        $values[] = "('static methodcall missed', '$staticMethodMissed')";
        
        $staticConstantCount  = $this->gremlin->query('g.V().hasLabel("Staticonstant").count()')[0];
        $staticConstantMissed = $this->gremlin->query('g.V().hasLabel("Staticonstant")
             .not(where(__.in("DEFINITION")))
             .not(where( __.out("CLASS").in("ANALYZED").has("analyzer", "Classes/IsExtClass")))
             .where( __.out("METHOD").has("token", "T_STRING"))
        ');
        if (is_array($staticConstantMissed)) {
            file_put_contents("{$this->config->log_dir}/staticconstant.missing.txt", implode(PHP_EOL, array_column($staticConstantMissed->toArray(), 'fullcode')));
            $staticConstantMissed = $staticConstantMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/staticconstant.missing.txt", 'Nothing found');
            $staticConstantMissed = 0;
        }
        $values[] = "('static constant total', '$staticConstantCount')";
        $values[] = "('static constant missed', '$staticConstantMissed')";

        $staticPropertyCount  = $this->gremlin->query('g.V().hasLabel("Staticproperty").count()')[0];
        $staticPropertyMissed = $this->gremlin->query('g.V().hasLabel("Staticproperty")
             .not(where(__.in("DEFINITION")))
             .not(where( __.out("CLASS").in("ANALYZED").has("analyzer", "Classes/IsExtClass")))
             .where( __.out("MEMBER").has("token", "T_VARIABLE"))
        ');
        if (is_array($staticPropertyMissed)) {
            file_put_contents("{$this->config->log_dir}/staticproperty.missing.txt", implode(PHP_EOL, array_column($staticPropertyMissed->toArray(), 'fullcode')));
            $staticPropertyMissed = $staticPropertyMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/staticproperty.missing.txt", 'Nothing found');
            $staticPropertyMissed = 0;
        }
        $values[] = "('static property total', '$staticPropertyCount')";
        $values[] = "('static property missed', '$staticPropertyMissed')";

        $constantCounts = $this->gremlin->query('g.V().hasLabel("Identifier", "Nsname").count()')[0];
        $constantMissed = $this->gremlin->query('g.V().hasLabel("Identifier", "Nsname")
             .not(has("token", within("T_CONST", "T_FUNCTION")))
             .not(where(__.in("DEFINITION")))
             .not(where(__.in("ANALYZED").has("analyzer", "Constants/IsExtConstant")))
             .not(where(__.in("NAME").hasLabel("Class", "Defineconstant", "Namespace", "As")))
             .not(where(__.in("EXTENDS", "IMPLEMENTS").hasLabel("Class", "Classanonymous", "Interface")))
             .not(where(__.in().hasLabel("Analysis", "Instanceof", "As", "Staticmethod", "Usetrait", "Usenamespace", "Member", "Constant", "Functioncall", "Methodcallname", "Staticmethodcall", "Staticproperty", "Staticclass", "Staticconstant", "Catch", "Parameter")))
             ') ?: array();
        if (is_array($constantMissed)) {
            file_put_contents("{$this->config->log_dir}/constant.missing.txt", implode(PHP_EOL, array_column($constantMissed->toArray(), 'fullcode')));
            $constantMissed = $constantMissed->toInt();
        } else {
            file_put_contents("{$this->config->log_dir}/constant.missing.txt", 'Nothing found');
            $constantMissed = 0;
        }
        $values[] = "('constant total', '$constantCounts')";
        $values[] = "('constant missed', '$constantMissed')";

        $query = 'INSERT INTO hash ("key", "value") VALUES ' . implode(', ', $values);
        $this->sqlite->query($query);
    }

    private function collectCyclomaticComplexity() {
        $values = array();
        $MAX_LOOPING = Analyzer::MAX_LOOPING;

        $query = $this->newQuery('CyclomaticComplexity');
        $query->atomIs(Analyzer::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->_as('name')
              ->back('first')
              ->outIs('BLOCK')
              ->raw(<<<GREMLIN
project("cc").by(
    __.emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).coalesce(
    __.hasLabel(
    "Ifthen", "Case", "Default", "Foreach", "For" ,"Dowhile", "While", "Continue", 
    "Catch", "Finally", "Throw", 
    "Ternary", "Coalesce"
    ),
    __.hasLabel("Ifthen").out("THEN", "ELSE"),
    __.hasLabel("Return").sideEffect{ ranked = it.get().value("rank");}.in("EXPRESSION").coalesce( __.filter{ it.get().value("count") != ranked + 1;},
                                                                                                   __.not(where(__.in("BLOCK").hasLabel("Function"))))
    ).count()
).select("first","cc").by("fullnspath").by()
GREMLIN
,array(), array());
        $query->prepareRawQuery();
        $cc = $this->gremlin->query($query->getQuery(), $query->getArguments());

        foreach($cc->toArray() as $row) {
            ++$row['cc'];
            $values[] = "('CyclomaticComplexity', '{$row['first']}', '{$row['cc']}')";
        }

        if(empty($values)) {
            return;
        }

        $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $values);
        $this->sqlite->query($query);
    }

    private function collectClassDepth() {
        $query = <<<'GREMLIN'
g.V().hasLabel('Class').groupCount('m').by(__.repeat( __.as("x").out("EXTENDS").in("DEFINITION") ).emit( ).times(2).count()).cap('m')
GREMLIN;
        $this->collectHashCounts($query, 'Class Depth');
    }

    private function collectParameterCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Function", "Method", "Closure", "Magicmethod").groupCount('m').by('count').cap('m'); 
GREMLIN;
        $this->collectHashCounts($query, 'ParameterCounts');
    }

    private function collectLocalVariableCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Function", "Method", "Closure", "Magicmethod").groupCount('m').by( __.out("DEFINITION").hasLabel("Variabledefinition", "Staticdefinition").count()).cap('m'); 
GREMLIN;
        $this->collectHashCounts($query, 'LocalVariableCounts');
    }

    private function collectMethodsCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous", "Trait").groupCount("m").by( __.out("METHOD", "MAGICMETHOD").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'MethodsCounts');
    }

    private function collectPropertyCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous", "Trait").groupCount("m").by( __.out("PPP").out("PPP").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassPropertyCounts');
    }

    private function collectClassTraitsCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous").groupCount("m").by( __.out("USE").out("USE").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassTraits');
    }

    private function collectClassInterfaceCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous").groupCount("m").by( __.out("IMPLEMENTS").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassInterfaces');
    }

    private function collectClassChildrenCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous").groupCount("m").by( __.out('EXTENDS').in("DEFINITION").hasLabel("Class", "Classanonymous").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassChildren');
    }

    private function collectConstantCounts() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Classanonymous", "Trait").groupCount("m").by( __.out("CONST").out("CONST").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassConstantCounts');
    }

    private function collectNativeCallsPerExpressions() {
        $MAX_LOOPING = Analyzer::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().hasLabel(within(["Sequence"])).groupCount("processed").by(count()).as("first").out("EXPRESSION").not(hasLabel(within(["Assignation", "Case", "Catch", "Class", "Classanonymous", "Closure", "Concatenation", "Default", "Dowhile", "Finally", "For", "Foreach", "Function", "Ifthen", "Include", "Method", "Namespace", "Php", "Return", "Switch", "Trait", "Try", "While"]))).as("results")
.groupCount("m").by( __.emit( ).repeat( __.out($this->linksDown).not(hasLabel("Closure", "Classanonymous")) ).times($MAX_LOOPING).hasLabel("Functioncall")
      .where( __.in("ANALYZED").has("analyzer", "Functions/IsExtFunction"))
      .count()
).cap("m")
GREMLIN;
        $this->collectHashCounts($query, 'NativeCallPerExpression');
    }

    private function collectClassChanges() {
        $this->sqlite->query('DROP TABLE IF EXISTS classChanges');
        $query = <<<'SQL'
CREATE TABLE classChanges (  
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    changeType   STRING,
    name         STRING,
    parentClass  TEXT,
    parentValue  TEXT,
    childClass   TEXT,
    childValue   TEXT
                    )
SQL;
        $this->sqlite->query($query);
        
        $total = 0;

        // TODO : Constant visibility and value

        $query = $this->newQuery('Constant Value');
        $query->atomIs('Constant', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')
              ->outIs('VALUE')
              ->savePropertyAs('fullcode', 'default1')
              ->inIs('VALUE')

              ->inIs('CONST')
              ->inIs('CONST')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class
              
              ->outIs('CONST')
              ->outIs('CONST')

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')

              ->outIs('VALUE')
              ->notSamePropertyAs('fullcode', 'default1', Analyzer::CASE_SENSITIVE) // test
              ->savePropertyAs('fullcode', 'default2') // collect

              ->raw(<<<'GREMLIN'
map{[ "name":name,
      "parent":class2,
      "parentValue":name + " = " + default2,
      "class":class1,
      "classValue":name + " = " + default1];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Constant Value', $query);

        $query = $this->newQuery('Constant visibility');
        $query->atomIs('Constant', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')

              ->inIs('CONST')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs('CONST')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->outIs('CONST')
              ->notSamePropertyAs('visibility', 'visibility1', Analyzer::CASE_SENSITIVE) // test
              ->savePropertyAs('visibility', 'visibility2') // collect
              ->outIs('CONST')

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')

              ->raw(<<<'GREMLIN'
map{[ "name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Constant visibility', $query);

        $query = $this->newQuery('Method Signature');
        $query->atomIs('Method', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')
              ->raw('sideEffect{ signature1 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }', array(), array())
              ->inIs('METHOD')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->raw('sideEffect{ signature2 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }.filter{ signature2 != signature1; }', array(), array())
              ->raw(<<<'GREMLIN'
map{["name":name,
      "parent":class2,
      "parentValue":"function " + name + "(" + signature2.join(", ") + ")",
      "class":class1,
      "classValue":"function " + name + "(" + signature1.join(", ") + ")"];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Method Signature', $query);

         $query = $this->newQuery('Method Visibility');
         $query->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'fnp')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs(array('METHOD', 'MAGICMETHOD'))
              ->savePropertyAs('fullcode', 'name1')
              ->back('first')
              ->inIs('OVERWRITE')
              ->savePropertyAs('visibility', 'visibility2')
              ->filter('visibility1  != visibility2;', array(), array())
              ->inIs('METHOD')
              ->savePropertyAs('fullcode', 'name2')
              ->raw(<<<'GREMLIN'
map{
     
     ["name":fnp.tokenize('::')[1],
      "parent":name1,
      "parentValue":visibility2 + ' ' + fnp.tokenize('::')[1],
      "class":name2,
      "classValue":visibility1 + ' ' + fnp.tokenize('::')[1]];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Method Visibility', $query);

        $query = $this->newQuery('Member Default');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('virtual', true)
              ->savePropertyAs('fullcode', 'name')
              ->outIs('DEFAULT')
              ->hasNoIn('RIGHT') // find an explicit default
              ->savePropertyAs('fullcode', 'default1')
              ->inIs('DEFAULT')
              ->inIs('PPP')
              ->inIs('PPP')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2')
              
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)

              ->outIs('DEFAULT')
              ->notSamePropertyAs('fullcode', 'default1', Analyzer::CASE_SENSITIVE)
              ->savePropertyAs('fullcode', 'default2')
              ->inIs('DEFAULT')
              ->raw(<<<'GREMLIN'
map{
     ["name":name,
      "parent":class2,
      "parentValue":name + ' = ' + default2,
      "class":class1,
      "classValue":name + ' = ' + default1];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Member Default', $query);

        $query = $this->newQuery('Member Visibility');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('virtual', true)
              ->savePropertyAs('fullcode', 'name')
              ->inIs('PPP')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs('PPP')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2')
              
              ->outIs('PPP')
              ->notSamePropertyAs('visibility', 'visibility1', Analyzer::CASE_SENSITIVE)
              ->savePropertyAs('visibility', 'visibility2')
              ->outIs('PPP')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)

              ->raw(<<<'GREMLIN'
map{
     ["name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
, array(), array());
        $total += $this->storeClassChangesNewQuery('Member Visibility', $query);

        display("Found $total class changes\n");
    }
    
    private function storeClassChanges($changeType, $query) {
        $index = $this->gremlin->query($query);
        
        return $this->storeInDump($changeType, $index);
    }

    private function storeClassChangesNewQuery($changeType, $query) {
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        
        return $this->storeInDump($changeType, $result);
    }
    
    private function storeInDump($changeType, $index) {
        $values = array();
        foreach($index->toArray() as $change) {
            $values[] = "('$changeType', 
                          '{$this->sqlite->escapeString($change['name'])}', 
                          '$change[parent]', 
                          '{$this->sqlite->escapeString($change['parentValue'])}', 
                          '{$change['class']}', 
                          '{$this->sqlite->escapeString($change['classValue'])}') ";
        }
        
        if (!empty($values)) {
            $query = 'INSERT INTO classChanges ("changeType", "name", "parentClass", "parentValue", "childClass", "childValue") VALUES ' . implode(', ', $values);
            $this->sqlite->query($query);
        }
        
        return count($values);
    }

    private function collectForeachFavorite() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Foreach").out("VALUE").not(hasLabel("Keyvalue")).values("fullcode")
GREMLIN;
        $valuesOnly = $this->gremlin->query($query);

        $query = <<<'GREMLIN'
g.V().hasLabel("Foreach").out("VALUE").out("INDEX").values("fullcode")
GREMLIN;
        $values = $this->gremlin->query($query);
        
        $query = <<<'GREMLIN'
g.V().hasLabel("Foreach").out("VALUE").out("INDEX").values("fullcode")
GREMLIN;
        $keys = $this->gremlin->query($query);

        $statsKeys = array_count_values($keys->toArray());
        $statsKeys['None'] = count($valuesOnly);

        $statsValues = array_count_values(array_merge($values->toArray(), $valuesOnly->toArray()));

        $valuesSQL = array();
        foreach($statsValues as $name => $count) {
            $valuesSQL[] = '("Foreach Values", "' . $this->sqlite->escapeString($name) . "\", $count)";
        }

        if (empty($valuesSQL)) {
            return 0;
        }
        $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $valuesSQL);
        $this->sqlite->query($query);
        
        return count($valuesSQL);
    }

    private function collectInclusions() {
        $this->sqlite->query('DROP TABLE IF EXISTS inclusions');
        $this->sqlite->query(<<<'GREMLIN'
CREATE TABLE inclusions (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           including STRING,
                           included STRING
                        )
GREMLIN
);

        $query = $this->newQuery('Including');
        $query->atomIs('Include', Analyzer::WITHOUT_CONSTANTS)
              ->_as('included')
              ->goToInstruction('File')
              ->_as('including')
              ->select(array('included'  => 'fullcode',
                             'including' => 'fullcode'));
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        
        if (empty($result->toArray())) {
            return 0;
        }

        $valuesSQL = array();
        foreach($result->toArray() as $row) {
            $valuesSQL[] = "('" . $this->sqlite->escapeString($row['including']) . "', '" . $this->sqlite->escapeString($row['included']) . "') \n";
        }

        $query = 'INSERT INTO inclusions ("including", "included") VALUES ' . implode(', ', $valuesSQL);
        $this->sqlite->query($query);

        return count($valuesSQL);
    }

    private function collectGlobalVariables() {
        $this->sqlite->query('DROP TABLE IF EXISTS globalVariables');
        $this->sqlite->query(<<<'GREMLIN'
CREATE TABLE globalVariables (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                variable STRING,
                                file STRING,
                                line INTEGER,
                                isRead INTEGER,
                                isModified INTEGER,
                                type STRING
                            )
GREMLIN
);

        $query = $this->newQuery('Global Variables');
        $query->atomIs('Virtualglobal', Analyzer::WITHOUT_CONSTANTS)
              ->codeIsNot('$GLOBALS', Analyzer::TRANSLATE, Analyzer::CASE_SENSITIVE)
              ->outIs('DEFINITION')
              ->savePropertyAs('label', 'type')
              ->outIsIE('DEFINITION')
              ->_as('variable')
              ->goToInstruction('File')
              ->savePropertyAs('fullcode', 'path')
              ->back('variable')
              ->raw(<<<GREMLIN
map{['file':path,
     'line' : it.get().value('line'),
     'variable' : it.get().value('fullcode'),
     'isRead' : 'isRead' in it.get().keys() ? 1 : 0,
     'isModified' : 'isModified' in it.get().keys() ? 1 : 0,
     'type' : type == 'Variabledefinition' ? 'implicit' : type == 'Globaldefinition' ? 'global' : '\$GLOBALS'
     ];

}
GREMLIN
,array(), array()
);
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        if ($result->toInt() === 0) {
            return 0;
        }

        $valuesSQL = array();
        foreach($result->toArray() as $row) {
            $valuesSQL[] = "('" . $this->sqlite->escapeString($row['variable']) . "', '$row[file]', $row[line], $row[isRead], $row[isModified], '$row[type]') \n";
        }

        $query = 'INSERT INTO globalVariables ("variable", "file", "line", "isRead", "isModified", "type") VALUES ' . implode(', ', $valuesSQL);
        $this->sqlite->query($query);

        return count($valuesSQL);
    }

    private function collectReadability() {
        $loops = 20;
        $query = <<<GREMLIN
g.V().sideEffect{ functions = 0; name=""; expression=0;}
    .hasLabel("Function", "Closure", "Method", "Magicmethod", "File")
    .not(where( __.out("BLOCK").hasLabel("Void")))
    .sideEffect{ ++functions; }
    .where(__.coalesce( __.out("NAME").sideEffect{ name=it.get().value("fullcode"); }.in("NAME"),
                        __.filter{true; }.sideEffect{ name="global"; file = it.get().value("fullcode");} )
    .sideEffect{ total = 0; expression = 0; type=it.get().label();}
    .coalesce( __.out("BLOCK"), __.out("FILE").out("EXPRESSION").out("EXPRESSION") )
    .repeat( __.out($this->linksDown).not(hasLabel("Class", "Function", "Closure", "Interface", "Trait", "Void")) ).emit().times($loops)
    .sideEffect{ ++total; }
    .not(hasLabel("Void"))
    .where( __.in("EXPRESSION", "CONDITION").sideEffect{ expression++; })
    .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ file = it.get().value("fullcode"); })
    .fold()
    )
    .map{ if (expression > 0) {
        ["name":name, "type":type, "total":total, "expression":expression, "index": 102 - expression - total / expression, "file":file];
    } else {
        ["name":name, "type":type, "total":total, "expression":0, "index": 100, "file":file];
    }
}    
GREMLIN;
        $index = $this->gremlin->query($query);

        $this->sqlite->query('DROP TABLE IF EXISTS readability');
        $query = <<<'SQL'
CREATE TABLE readability (  
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    name    STRING,
    type    STRING,
    tokens  INTEGER,
    expressions INTEGER,
    file        STRING
                    )
SQL;
        $this->sqlite->query($query);

        $values = array();
        foreach($index as $row) {
            $values[] = "('$row[name]', '$row[type]', $row[total], $row[expression], '{$this->sqlite->escapeString($row['file'])}') ";
        }

        $query = 'INSERT INTO readability ("name", "type", "tokens", "expressions", "file") VALUES ' . implode(', ', $values);
        $this->sqlite->query($query);

        display( count($values) . ' readability index');
    }

    public function checkRulesets($theme, array $analyzers) {
        $sqliteFile = $this->config->dump;
        
        $sqlite = new \Sqlite3($sqliteFile);
        $sqlite->busyTimeout(5000);

        $query = 'SELECT analyzer FROM resultsCounts WHERE analyzer IN (' . makeList($analyzers) . ')';
        $ran = array();
        $res = $sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $ran[] = $row['analyzer'];
        }
        
        if (empty(array_diff($analyzers, $ran))) {
            $query = "INSERT INTO themas (\"id\", \"thema\") VALUES (null, \"$theme\")";
            $sqlite->query($query);
        }
    }

    private function expandRulesets() {
        $analyzers = array();
        $res = $this->sqlite->query('SELECT analyzer FROM resultsCounts');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $analyzers[] = $row['analyzer'];
        }

        $res = $this->sqlite->query('SELECT thema FROM themas');
        $ran = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $ran[$row['thema']] = 1;
        }

        $themas = $this->themes->listAllRulesets();
        $themas = array_diff($themas, $ran);

        $add = array();
        
        $themes = $this->themes->getRulesetsAnalyzers($themas);
        if (empty(array_diff($themes, $analyzers))) {
            $add[] = $theme;
        }
        
        if (!empty($add)) {
            $query = 'INSERT INTO themas (thema) VALUES ("' . implode('"), ("', $add) . '")';
            $this->sqlite->query($query);
        }
    }
    
    private function initDump() {
        if (file_exists($this->sqliteFile)) {
            unlink($this->sqliteFile);
        }
        $this->sqlite = new \Sqlite3($this->sqliteFile);

        $query = <<<'SQL'
CREATE TABLE themas (  id    INTEGER PRIMARY KEY AUTOINCREMENT,
                       thema STRING,
                       CONSTRAINT "themas" UNIQUE (thema) ON CONFLICT IGNORE
                    )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
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

        $query = <<<'SQL'
CREATE TABLE resultsCounts ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                             analyzer STRING,
                             count INTEGER DEFAULT -6,
                            CONSTRAINT "analyzers" UNIQUE (analyzer) ON CONFLICT REPLACE
                           )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE hashAnalyzer ( id INTEGER PRIMARY KEY,
                            analyzer TEXT,
                            key TEXT UNIQUE,
                            value TEXT
                          );
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE hashResults ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                            name TEXT,
                            key TEXT,
                            value TEXT
                          );
SQL;
        $this->sqlite->query($query);

        $this->collectDatastore();

        $time   = time();
        try {
            $id     = random_int(0, PHP_INT_MAX);
        } catch (\Throwable $e) {
            die("Couldn't generate an id for the current dump file. Aborting");
        }
        if (file_exists($this->sqliteFilePrevious)) {
            $sqliteOld = new \Sqlite3($this->sqliteFilePrevious);

            $presence = $sqliteOld->querySingle('SELECT count(*) FROM sqlite_master WHERE type="table" AND name="hash"');
            if ($presence == 1) {
                $serial = $sqliteOld->querySingle('SELECT value FROM hash WHERE key="dump_serial"') + 1;
            } else {
                $serial = 0;
            }
        } else {
            $serial = 1;
        }
        $query = <<<SQL
INSERT INTO hash VALUES (NULL, 'dump_time', $time),
                        (NULL, 'dump_id', $id),
                        (NULL, 'dump_serial', $serial)
SQL;
        $this->sqlite->query($query);

        display('Inited tables');
    }
    
    private function newQuery($title) {
        return new Query(0, $this->config->project, $title, null, $this->datastore);
    }
}

?>
