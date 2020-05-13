<?php
/*
 * Copyright 2012-2019 Damien Seguy - Exakat SAS <contact(at)exakat.io>
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

declare(strict_types = 1);

namespace Exakat\Tasks;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Dump\AnalyzerDump;
use Exakat\Config;
use Exakat\Exceptions\MissingGremlin;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchRuleset;
use Exakat\Exceptions\NotProjectInGraph;
use Exakat\GraphElements;
use Exakat\Log;
use Exakat\Query\Query;
use Exakat\Dump\Dump as DumpDb;

class Dump extends Tasks {
    const CONCURENCE = self::DUMP;

    private $files = array();

    protected $logname = self::LOG_NONE;

    private $linksDown = '';
    private $dump      = null;

    const WAITING_LOOP = 1000;

    public function __construct(bool $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($subTask);

        $this->log = new Log('dump',
                             $this->config->project_dir);

        $this->linksDown = GraphElements::linksAsList();
    }

    public function setConfig(Config $config): void {
        $this->config = $config;
    }

    public function run(): void {

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        if ($this->config->gremlin === 'NoGremlin') {
            throw new MissingGremlin();
        }

        $projectInGraph = $this->gremlin->query('g.V().hasLabel("Project").values("code")');
        if (empty($projectInGraph)) {
            throw new NoSuchProject($this->config->project);
        }
        $projectInGraph = $projectInGraph[0];

        if ($projectInGraph !== (string) $this->config->project) {
            throw new NotProjectInGraph($this->config->project, $projectInGraph);
        }
// TODO
//        $this->sqliteFilePrevious = $this->config->dump_previous;
// also baseline

        // move this to .dump.sqlite then rename at the end, or any imtermediate time
        // Mention that some are not yet arrived in the snitch
        $this->addSnitch();

        if ($this->config->update !== true && file_exists($this->config->dump)) {
            unlink($this->config->dump);
        }
        $this->dump = DumpDb::factory($this->config->dump, DumpDb::INIT);

        if ($this->config->collect === true) {
            display('Collecting data');

            $this->collect();
        }

        $this->loadSqlDump();

        $counts = array();
        $datastore = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READONLY);
        $datastore->busyTimeout(\SQLITE3_BUSY_TIMEOUT);
        $res = $datastore->query('SELECT * FROM analyzed');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = (int) $row['counts'];
        }
        $this->log->log('count analyzed : ' . count($counts) . "\n");
        $this->log->log('counts ' . implode(', ', $counts) . "\n");
        $datastore->close();
        unset($datastore);

        if (!empty($this->config->project_rulesets)) {
            $ruleset = $this->config->project_rulesets;
            if ($ruleset === array('None')) {
                $rulesets = array();
            } else {
                $rulesets = $this->rulesets->getRulesetsAnalyzers($ruleset);
                if (empty($rulesets)) {
                    $r = $this->rulesets->getSuggestionRuleset($ruleset);
                    if (!empty($r)) {
                        echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
                    }

                    throw new NoSuchRuleset(implode(', ', $ruleset));
                }
                $missing = $this->processResultsRuleset($ruleset, $counts);
                $this->expandRulesets();
                $this->collectHashAnalyzer();

                if ($missing === 0) {
                    $this->storeToDumpArray('themas', array_map(function (string $x) { return array('', $x); }, $ruleset));
                    $rulesets = array();
                }
            }

        } elseif (!empty($this->config->program)) {
            $analyzer = $this->config->program;
            if(is_array($analyzer)) {
                $rulesets = $analyzer;
            } else {
                $rulesets = array($analyzer);
            }

            $rulesets = array_unique($rulesets);
            foreach($rulesets as $id => $ruleset) {
                if (!$this->rulesets->getClass($ruleset)) {
                    display('No such analyzer as ' . $ruleset . '. Omitting.');
                    unset($rulesets[$id]);
                }
            }
            display('Processing ' . count($rulesets) . ' analyzer' . (count($rulesets) > 1 ? 's' : '') . ' : ' . implode(', ', $rulesets));

            if(count($rulesets) > 1) {
                $this->processResultsList($rulesets, $counts);
                $this->expandRulesets();
                $this->collectHashAnalyzer();
            } elseif (empty($rulesets)) {
                throw new NoSuchAnalyzer($ruleset, $this->rulesets);
            } else {
                $analyzer = array_pop($rulesets);
                if (isset($counts[$analyzer])) {
                    $this->processMultipleResults(array($analyzer), $counts);
                    $this->collectHashAnalyzer();
                    $rulesets = array();
                } else {
                    display("$analyzer is not run yet.");
                }
            }

        } else {
            $rulesets = array();
        }

        $this->log->log('Still ' . count($rulesets) . " to be processed\n");
        display('Still ' . count($rulesets) . " to be processed\n");

        $this->finish();
    }

    public function finalMark(array $finalMark): void {
        $sqlite = new \Sqlite3($this->config->dump);
        $sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $values = array();
        foreach($finalMark as $key => $value) {
            $values[] = "(null, '$key', '$value')";
        }

        $sqlite->query('REPLACE INTO hash VALUES ' . implode(', ', $values));
    }

    private function processResultsRuleset(array $ruleset, array $counts = array()): int {
        $analyzers = $this->rulesets->getRulesetsAnalyzers($ruleset);

        return $this->processMultipleResults($analyzers, $counts);
    }

    private function processResultsList(array $rulesetList, array $counts = array()): int {
        return $this->processMultipleResults($rulesetList, $counts);
    }

    private function processMultipleResults(array $analyzers, array $counts): int {
        $specials = array('Php/Incompilable',
                          'Composer/UseComposer',
                          'Composer/UseComposerLock',
                          'Composer/Autoload',
                          );
        $diff = array_intersect($specials, $analyzers);
        if (!empty($diff)) {
            $this->dump->removeResults($diff);
            foreach($diff as $d) {
                $this->processResults($d, $counts[$d] ?? -3);
            }
            $analyzers = array_diff($analyzers, $diff);
        }

        $saved = 0;
        $docs = exakat('docs');
        $severities = array();
        $readCounts = array();

        $skipAnalysis = array();
        $analyzers = array_filter($analyzers, function (string $s): bool { return substr($s, 0, 9) !== 'Complete/' && substr($s, 0, 5) !== 'Dump/'; });
        // Remove analysis that are not exported via dump
        foreach($analyzers as $id => $analyzer) {
            $a = $this->rulesets->getInstance($analyzer);
            if ($a instanceof AnalyzerDump) {
                unset($analyzers[$id]);
                $skipAnalysis[] = $analyzer;
            }
        }
        $this->dump->removeResults($analyzers);

        $chunks = array_chunk($analyzers, 200);
        // Gremlin only accepts chunks of 255 maximum

        foreach($chunks as $chunk) {
            $query = $this->newQuery('processMultipleResults');
            $query->atomIs('Analysis', Analyzer::WITHOUT_CONSTANTS)
                  ->is('analyzer', $chunk)
                  ->savePropertyAs('analyzer', 'analyzer')
                  ->outIs('ANALYZED')
                  ->initVariable(array('ligne',                  'fullcode_',                  'file', 'theFunction', 'theClass', 'theNamespace'),
                                 array('it.get().value("line")', 'it.get().value("fullcode")', '"None"', '""', '""', '""'),
                                )
            ->raw(<<<GREMLIN
where( __.until( hasLabel("Project") ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() in ["Function", "Closure", "Arrayfunction", "Magicmethod", "Method"]) { theFunction = it.get().value("fullcode")} }
      .sideEffect{ if (it.get().label() in ["Class", "Trait", "Interface", "Classanonymous"]) { theClass = it.get().value("fullcode")} }
      .sideEffect{ if (it.get().label() == "File") { file = it.get().value("fullcode")} }
       ).fold()
)
GREMLIN
)
            ->getVariable(array('fullcode_', 'file', 'ligne', 'theNamespace', 'theClass', 'theFunction', 'analyzer'),
                          array('fullcode',  'file', 'line' , 'namespace',    'class',    'function',    'analyzer'));
            $query->prepareRawQuery();
            $res = $this->gremlin->query($query->getQuery(), $query->getArguments())->toArray();

            $toDump = array();
            foreach($res as $result) {
                if (empty($result)) {
                    continue;
                }

                if (isset($severities[$result['analyzer']])) {
                    $severity = $severities[$result['analyzer']];
                } else {
                    $severity = $docs->getDocs($result['analyzer'])['severity'];
                    $severities[$result['analyzer']] = $severity;
                }

                $toDump[] = array($result['fullcode'],
                                  $result['file'],
                                  $result['line'],
                                  $result['namespace'],
                                  $result['class'],
                                  $result['function'],
                                  $result['analyzer'],
                                  $severity,
                                  );
            }

            $readCounts[] = $this->dump->addResults($toDump);
        }
        $readCounts = array_merge(...$readCounts);

        $this->log->log(implode(', ', $analyzers) . " : dumped $saved");

        $error = 0;
        $emptyResults = $skipAnalysis;
        foreach($analyzers as $class) {
            if (!isset($counts[$class]) || $counts[$class] < 0) {
                continue;
            }

            if ($counts[$class] === 0 && !isset($readCounts[$class])) {
                display("No results saved for $class\n");
                $emptyResults[] = $class;
            } elseif ($counts[$class] === ($readCounts[$class] ?? 0)) {
                display("All $counts[$class] results saved for $class\n");
            } else {
                assert(($counts[$class] ?? 0) === ($readCounts[$class] ?? 0), "'results were not correctly dumped in $class : $readCounts[$class]/$counts[$class]");
                ++$error;
            }
        }

        $this->dump->addEmptyResults($emptyResults);

        return $error;
    }

    private function processResults(string $class, int $count): void {
        $this->log->log( "$class : $count\n");
        // No need to go further
        if ($count <= 0) {
            $saved = $this->dump->addEmptyResults(array($class));
            return;
        }

        $analyzer = $this->rulesets->getInstance($class, $this->gremlin, $this->config);
        $res = $analyzer->getDump();

        $saved = 0;
        $docs = exakat('docs');
        $severity = $docs->getDocs($class)['severity'];

        $toDump = array();
        foreach($res as $result) {
            if (empty($result)) {
                continue;
            }

            $toDump[] = array($result['fullcode'],
                              $result['file'],
                              $result['line'],
                              $result['namespace'],
                              $result['class'],
                              $result['function'],
                              $class,
                              $severity,
                              );
        }

        if (empty($toDump)) {
            $saved = $this->dump->addEmptyResults(array($class));
            return;
        }

        $saved = $this->dump->addResults($toDump);
        $saved = $saved[$class];

        $this->log->log("$class : dumped " . $saved);

        if ($count === $saved) {
            display('All ' . $saved . " results saved for $class\n");
        } else {
            assert($count === $saved, "'results were not correctly dumped in $class : " . $saved . "/$count");
            display('' . $saved . " results saved, $count expected for $class\n");
        }
    }

    private function getAtomCounts(): void {
        $query = 'g.V().groupCount("b").by(label).cap("b").next();';
        $atomsCount = $this->gremlin->query($query);
        $atomsCount->deHash();

        $this->dump->storeInTable('atomsCounts', $atomsCount);

        display(count($atomsCount) . " atoms\n");
    }

    private function finish(): void {
        $this->dump->close();
        $this->removeSnitch();
    }

    private function collectHashAnalyzer(): void {
        $tables = array('hashAnalyzer',
                       );
        $this->dump->collectTables($tables);
    }

    private function collectVariables(): void {
        $query = $this->newQuery('collectVariables');
        $query->atomIs(array('Variable', 'Variablearray', 'Variableobject'), Analyzer::WITHOUT_CONSTANTS)
              ->tokenIs('T_VARIABLE')
              ->initVariable(array('name',                       'type'),
                             array('it.get().value("fullcode")', 'it.get().label()'))
              ->getVariable(array('name', 'type'));
        $query->prepareRawQuery();
        $variables = $this->gremlin->query($query->getQuery(), $query->getArguments())->toArray();

        $toDump = array();

        $types = array('Variable'       => 'var',
                       'Variablearray'  => 'array',
                       'Variableobject' => 'object',
                      );
        $unique = array();
        foreach($variables as $row) {
            if (isset($unique[$row['name'] . $row['type']])) {
                continue;
            }
            $name = str_replace(array('&', '...', '@'), '', $row['name']);
            $unique[$name . $row['type']] = 1;
            $type = $types[$row['type']];
            $toDump[] = array('',
                              mb_strtolower($name),
                              $type,
                             );
        }
        $total = $this->storeToDumpArray('variables', $toDump);
        display("$total variables\n");
    }

    private function collectStructures(): void {
        $namespacesId = $this->collectStructures_namespaces();

        $MAX_LOOPING = Analyzer::MAX_LOOPING;
        $query = $this->newQuery('cit classes');
        $query->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
 sideEffect{ extendList = ''; }.where(__.out("EXTENDS").optional(__.out('DEFINITION').where(__.in('USE'))).sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ implementList = []; }.where(__.out("IMPLEMENTS").optional(__.out('DEFINITION').where(__.in('USE'))).sideEffect{ implementList.push( it.get().value("fullcode"));}.fold() )
.sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Usetrait").out("USE").optional(__.out('DEFINITION').where(__.in('USE'))).sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "USE", "PPP", "CONST").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = '';}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.sideEffect{ phpdoc = ''; }.where(__.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold() )
.map{ 
        ['id' : '',
         'fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'namespace': 1,
         'type':'class',
         'abstract':it.get().properties("abstract").any(),
         'final':it.get().properties("final").any(),
         'phpdoc':phpdoc,
         'begin':lines.min(),
         'end':lines.max(),
         'file':file,
         'line':it.get().value("line"),

         'extends':extendList,
         'implements':implementList,
         'uses':useList
         ];
}
GREMLIN
);
        $query->prepareRawQuery();
        $classes = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;

        $cit = array();
        $citId = array();
        $cit_implements = array();
        $cit_use = array();
        $citCount = $this->dump->getTableCount('cit');

        foreach($classes as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }

            $cit_implements[$row['line'] . $row['fullnspath']] = $row['implements'];
            unset($row['implements']);
            $cit_use[$row['line'] . $row['fullnspath']] = $row['uses'];
            unset($row['uses']);
            $citId[$row['line'] . $row['fullnspath']] = ++$citCount;
            unset($row['fullnspath']);
            $row['namespace'] = $namespaceId;
            $cit[] = $row;

            ++$total;
        }
        display("$total classes\n");

        // Interfaces
        $query = $this->newQuery('cit interfaces');
        $query->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
 sideEffect{ extendList = ''; }.where(__.out("EXTENDS").sideEffect{ extendList = it.get().value("fullnspath"); }.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "CONST").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = [];}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.sideEffect{ phpdoc = ''; }.where(__.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold() )
.map{ 
        ['id' : '',
         'fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'namespace': 1,
         'type':'interface',
         'abstract':0,
         'final':0,
         'phpdoc':phpdoc,
         'begin':lines.min(),
         'end':lines.max(),
         'file':file,
         'line':it.get().value("line"),

         'extends':extendList,
         ];
}

GREMLIN
);
        $query->prepareRawQuery();
        $interfaces = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;
        foreach($interfaces as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }

            $citId[$row['line'] . $row['fullnspath']] = ++$citCount;
            unset($row['fullnspath']);
            $row['namespace'] = $namespaceId;

            $cit[] = $row;

            ++$total;
        }

        display("$total interfaces\n");

        // Traits
        $query = $this->newQuery('cit traits');
        $query->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
 sideEffect{ useList = []; }.where(__.out("USE").hasLabel("Usetrait").out("USE").sideEffect{ useList.push( it.get().value("fullnspath"));}.fold() )
.sideEffect{ lines = [];}.where( __.out("METHOD", "USE", "PPP").emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).sideEffect{ lines.add(it.get().value("line")); }.fold())
.sideEffect{ file = '';}.where( __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File")).hasLabel("File").sideEffect{ file = it.get().value("fullcode"); }.fold() )
.sideEffect{ phpdoc = ''; }.where(__.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold() )
.map{ 
        ['id' : '',
         'fullnspath':it.get().value("fullnspath"),
         'name': it.get().vertices(OUT, "NAME").next().value("fullcode"),
         'namespace': 1,
         'type':'trait',
         'abstract':0,
         'final':0,
         'phpdoc':phpdoc,
         'begin':lines.min(),
         'end':lines.max(),
         'file':file,
         'line':it.get().value("line"),
         
         'extends':0,
         'implements':0,
         'uses':useList
         ];
}

GREMLIN
);
        $query->prepareRawQuery();
        $traits = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;
        foreach($traits as $row) {
            $namespace = preg_replace('#\\\\[^\\\\]*?$#is', '', $row['fullnspath']);

            if (isset($namespacesId[$namespace])) {
                $namespaceId = $namespacesId[$namespace];
            } else {
                $namespaceId = 1;
            }

            $row['implements'] = array(); // always empty

            unset($row['implements']);
            $cit_use[$row['line'] . $row['fullnspath']] = $row['uses'];
            unset($row['uses']);
            $citId[$row['line'] . $row['fullnspath']] = ++$citCount;
            unset($row['fullnspath']);
            $row['namespace'] = $namespaceId;

            $cit[] = $row;

            ++$total;
        }
        display("$total traits\n");

        if (!empty($cit)) {
            foreach($cit as &$aCit) {
                if (empty($aCit['extends'])) {
                    continue;
                }

                $citIds = preg_grep('/^\d+' . preg_quote($aCit['extends'], '/') . '$/i', array_keys($citId));

                if (!empty($citIds)) {
                    $aCit['extends'] = intval($citId[array_pop($citIds)]);
                }
            }
            $this->storeToDumpArray('cit', $cit);

            $toDump = array();
            foreach($cit_implements as $id => $impl) {
                foreach($impl as $implements) {
                    $citIds = preg_grep('/^\d+\\\\' . addslashes(mb_strtolower($implements)) . '$/', array_keys($citId));

                    if (empty($citIds)) {
                        $toDump[] = array('', $citId[$id], $implements, 'implements');
                    } else {
                        // Here, we are missing the one that are not found
                        foreach($citIds as $c) {
                            $toDump[] = array('', $citId[$id], $citId[$c], 'implements');
                        }
                    }
                }
            }
            $total = $this->storeToDumpArray('cit_implements', $toDump);
            display("$total implements \n");

            $toDump = array();
            foreach($cit_use as $id => $use1) {
                foreach($use1 as $uses) {
                    $citIds = preg_grep('/^\d+\\\\' . addslashes(mb_strtolower($uses)) . '$/', array_keys($citId));

                    if (empty($citIds)) {
                        $toDump[] = array('', $citId[$id], $uses, 'use');
                    } else {
                        // Here, we are missing the one that are not found
                        foreach($citIds as $c) {
                            $toDump[] = array('', $citId[$id], $citId[$c], 'use');
                        }
                    }
                }
            }
            $total = $this->storeToDumpArray('cit_implements', $toDump);
            display("$total uses\n");
        }

        // Methods
        $methodCount = 0;
        $methodIds = array();
        $query = $this->newQuery('cit methods');
        $query->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
     coalesce( 
            __.out("BLOCK").out("EXPRESSION").hasLabel("As"),
            __.hasLabel("Method", "Magicmethod")
     )
     .sideEffect{ 
        returntype = 'None';
        phpdoc     = '';
      }
     .where(
        __.coalesce( 
            __.out("AS").sideEffect{alias = it.get().value("fullcode")}.in("AS")
              .out("NAME").in("DEFINITION").hasLabel("Method", "Magicmethod"), 
            __.sideEffect{ alias = false; }
          )
         .as("method")
         .in("METHOD", "MAGICMETHOD").hasLabel("Class", "Interface", "Trait").sideEffect{classe = it.get().value("fullnspath"); classline =  it.get().value("line"); }
         .select("method")
         .where( __.sideEffect{ lines = [];}
                   .out("BLOCK").out("EXPRESSION")
                   .emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING)
                   .sideEffect{ lines.add(it.get().value("line")); }
                   .fold()
          )
          .where( __.out('RETURNTYPE').sideEffect{ returntype = it.get().value("fullcode")}.fold())
          .where( __.out('PHPDOC').sideEffect{ phpdoc = it.get().value("fullcode")}.fold())
          .where( __.out('NAME').sideEffect{ name = it.get().value("fullcode")}.fold())
          .map{ 

    if (alias == false) {
        signature = it.get().value("fullcode");
    } else {
        signature = it.get().value("fullcode").replaceFirst("function .*?\\\\(", "function "+alias+"(" );
    }
        }
      ) 
.map{["signature": signature,
         "name":name,
         "abstract":it.get().properties("abstract").any(),
         "final":it.get().properties("final").any(),
         "static":it.get().properties("static").any(),
         "returntype": returntype,

         "public":    it.get().value("visibility") == "public",
         "protected": it.get().value("visibility") == "protected",
         "private":   it.get().value("visibility") == "private",
         "class":     classe,
         "phpdoc":    phpdoc,
         "begin":     lines.min(),
         "end":       lines.max(),
         "classline": classline
         ];}

GREMLIN
);
        $query->prepareRawQuery();
        $methods = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;
        $toDump = array();
        $unique = array();
        foreach($methods as $row) {
            if ($row['public']) {
                $visibility = 'public';
            } elseif ($row['protected']) {
                $visibility = 'protected';
            } elseif ($row['private']) {
                $visibility = 'private';
            } else {
                $visibility = '';
            }

            if (!isset($citId[$row['classline'] . $row['class']])) {
                continue;
            }
            $methodId = $row['class'] . '::' . mb_strtolower($row['name']);
            if (isset($methodIds[$methodId])) {
                continue; // skip double
            }
            $methodIds[$methodId] = ++$methodCount;

            $toDump[] = array($methodCount,
                             $row['name'],
                             $citId[$row['classline'] . $row['class']],
                             (int) $row['static'],
                             (int) $row['final'],
                             (int) $row['abstract'],
                             $visibility,
                             $row['returntype'],
                             (int) $row['begin'],
                             (int) $row['end'],
                             $row['phpdoc']
                             );
            ++$total;
        }
        $total = $this->storeToDumpArray('methods', $toDump);

        // Arguments
        $query = $this->newQuery('Method parameters');
        $query->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('ARGUMENT')
              ->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<'GREMLIN'
where( __.out('NAME').sideEffect{ methode = it.get().value("fullcode").toString().toLowerCase() }.fold())
GREMLIN
)
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Class', 'Interface', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
             ->savePropertyAs('fullnspath', 'classe')
             ->savePropertyAs('line', 'classline')
             ->back('first')
             ->raw(<<<'GREMLIN'
sideEffect{
    init = 'None';
    typehint = 'None';
}
.where( __.out('NAME').sideEffect{ name = it.get().value("fullcode")}.fold())
.where( __.out('TYPEHINT').sideEffect{ typehint = it.get().value("fullcode")}.fold())
.where( __.out('DEFAULT').not(where(__.in("RIGHT"))).sideEffect{ init = it.get().value("fullcode")}.fold())
.map{ 
    x = ["name": name,
         "rank":it.get().value("rank"),
         "variadic":it.get().properties("variadic").any(),
         "reference":it.get().properties("reference").any(),

         "classe":classe,
         "methode":methode,
         "line": it.get().value("line"),
         "classline": classline,

         "init": init,
         "typehint":typehint,
         ];
}

GREMLIN
);
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;
        $toDump = array();
        foreach($result->toArray() as $row) {
            $toDump[] = array('',
                              $row['name'],
                              (int) $row['rank'],
                              (int) $citId[$row['classline'] . $row['classe']],
                               $methodIds[$row['classe'] . '::' . $row['methode']],
                               $row['init'],
                               (int) $row['reference'],
                               (int) $row['variadic'],
                               $row['typehint'],
                               (int) $row['line'],
            );
        }
        $total = $this->storeToDumpArray('arguments', $toDump);
        display("$total arguments\n");

        // Properties
        $query = <<<'GREMLIN'
g.V().hasLabel("Propertydefinition").as("property")
     .in("PPP")
.sideEffect{ 
    x_static = it.get().properties("static").any();
    x_public = it.get().value("visibility") == "public";
    x_protected = it.get().value("visibility") == "protected";
    x_private = it.get().value("visibility") == "private";
    x_var = it.get().value("token") == "T_VAR";
    phpdoc = '';
    v = '';
}
     .where( __.out('DEFAULT').not(where( __.in("RIGHT"))).sideEffect{ v = it.get().value("fullcode")}.fold())
     .where( __.out('PHPDOC').sideEffect{ phpdoc = it.get().value("fullcode")}.fold())
     .in("PPP").hasLabel("Class", "Interface", "Trait")
     .sideEffect{classe = it.get().value("fullnspath"); }
     .sideEffect{classline = it.get().value("line"); }
     .select("property")
.map{ 
    b = it.get().value("fullcode").tokenize(' = ');
    name = b[0];

   ["class":classe,
    "static":x_static,
    "public":x_public,
    "protected":x_protected,
    "private":x_private,
    "var":x_var,
    "name": name,
    "value": v,
    "phpdoc":phpdoc,
    "classline":classline
    ];
}

GREMLIN;
        $result = $this->gremlin->query($query);

        $total = 0;
        $toDump = array();
        $propertyIds = array();
        $propertyCount = 0;
        foreach($result->toArray() as $row) {
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
            if (!isset($citId[$row['classline'] . $row['class']])) {
                continue;
            }
            $propertyId = $row['class'] . '::' . $row['name'];
            if (isset($propertyIds[$propertyId])) {
                continue; // skip double
            }
            $propertyIds[$propertyId] = ++$propertyCount;

            $toDump[] = array('',
                              $row['name'],
                              (int) $citId[$row['classline'] . $row['class']],
                              $visibility,
                               $row['value'],
                               (int) $row['static'],
                               $row['phpdoc'],
            );
        }
        $total = $this->storeToDumpArray('properties', $toDump);
        display("$total properties\n");

        // Class Constant
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Trait")
.sideEffect{ 
    line = it.get().value("line");
    classe = it.get().value("fullnspath");
}
     .out('CONST')
.sideEffect{ 
    x_public    = it.get().value("visibility") == 'public';
    x_protected = it.get().value("visibility") == 'protected';
    x_private   = it.get().value("visibility") == 'private';
    phpdoc = '';
}
     .where( __.out('PHPDOC').sideEffect{ phpdoc = it.get().value("fullcode")}.fold())
     .where( __.out('CONST').out('NAME').sideEffect{ name = it.get().value("fullcode")}.fold())
     .where( __.out('CONST').out('VALUE').sideEffect{ valeur = it.get().value("fullcode")}.fold())
     .map{ 
    x = ["name": name,
         "value": valeur,
         "public":x_public,
         "protected":x_protected,
         "private":x_private,
         "class": classe,
         "phpdoc": phpdoc,
         "line": line
         ];
}

GREMLIN;
        $res = $this->gremlin->query($query);
        $total = 0;
        $toDump = array();

        foreach($result->toArray() as $row) {
            if ($row['public']) {
                $visibility = 'public';
            } elseif ($row['protected']) {
                $visibility = 'protected';
            } elseif ($row['private']) {
                $visibility = 'private';
            } else {
                continue;
            }

            // If we haven't found any definition for this class, just ignore it.
            if (!isset($citId[$row['classline'] . $row['class']])) {
                continue;
            }
            $propertyId = $row['class'] . '::' . $row['name'];
            if (isset($propertyIds[$propertyId])) {
                continue; // skip double
            }
            $propertyIds[$propertyId] = ++$propertyCount;

            $toDump[] = array('',
                              $row['name'],
                              (int) $citId[$row['classline'] . $row['class']],
                              $visibility,
                              $row['value'],
                              $row['phpdoc'],
            );
        }
        $total = $this->storeToDumpArray('classconstants', $toDump);
        display("$total class constants\n");

        // Global Constants
        $query = $this->newQuery('Constants define()');
        $query->atomIs('Defineconstant', Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<'GREMLIN'
 sideEffect{ 
    file = ""; 
    namespace = "\\"; 
    phpdoc = "";
}
.where( __.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold())
.where( 
    __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File"))
           .coalesce( 
                __.hasLabel("File").sideEffect{ file = it.get().value("fullcode"); },
                __.hasLabel("Namespace").sideEffect{ namespace = it.get().value("fullnspath"); }
                )
           .fold() 
)
GREMLIN
)
              ->filter(
                $query->side()
                     ->outIs('NAME')
                     ->is('constant', true)
                     ->savePropertyAs('fullcode', 'name')
                     ->prepareSide(),
                     array()
              )
              ->filter(
                $query->side()
                     ->outIs('VALUE')
                     ->is('constant', true)
                     ->savePropertyAs('fullcode', 'v')
                     ->prepareSide(),
                     array()
              )
              ->raw(<<<'GREMLIN'
map{ ["name":name, 
      "value":v, 
      "namespace": namespace, 
      "file": file, 
      "type":"define",
      "phpdoc":phpdoc
    ]; 
}
GREMLIN
);
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $total = 0;
        $toDump = array();
        foreach($result->toArray() as $row) {
            if (isset($namespacesId[$row['namespace']])) {
                $namespaceId = $namespacesId[$row['namespace']];
            } else {
                $namespaceId = 1;
            }

            $toDump[] = array('',
                              trim($row['name'], "'\""),
                              $namespaceId,
                              $this->files[$row['file']],
                              $row['value'],
                              $row['type'],
                              $row['phpdoc'],
            );
        }
        $total = $this->storeToDumpArray('constants', $toDump);
        display("$total global constants\n");

        $query = $this->newQuery('Constants const');
        $query->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<'GREMLIN'
 sideEffect{ 
    file = ""; 
    namespace = "\\"; 
    phpdoc = "";
}
.where( 
    __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File"))
           .coalesce( 
                __.hasLabel("File").sideEffect{ file = it.get().value("fullcode"); },
                __.hasLabel("Namespace").sideEffect{ namespace = it.get().value("fullnspath"); }
                )
           .fold() 
)
.where( __.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold())
GREMLIN
)
              ->hasNoIn('CONST') // Not class or interface
              ->outIs('CONST')
              ->atomIs('Constant', Analyzer::WITHOUT_CONSTANTS)
              ->filter(
                $query->side()
                     ->outIs('NAME')
                     ->is('constant', true)
                     ->savePropertyAs('fullcode', 'name')
                     ->prepareSide(),
                     array()
              )
              ->filter(
                $query->side()
                     ->outIs('VALUE')
                     ->is('constant', true)
                     ->savePropertyAs('fullcode', 'v')
                     ->prepareSide(),
                     array()
              )

              ->raw('map{ ["name":name, 
                           "value":v, 
                           "namespace": namespace, 
                           "file": file, 
                           "type":"const",
                           "phpdoc": phpdoc
                           ]; }');
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $toDump = array();
        foreach($result->toArray() as $row) {
            $toDump[] = array('',
                              $row['name'],
                              $namespacesId[$row['namespace']] ?? 1,
                              $this->files[$row['file']],
                              $row['value'],
                              $row['type'],
                              $row['phpdoc'],
                            );
        }

        $total = $this->storeToDumpArray('constants', $toDump);
        display("$total const constants\n");

        // Collect Functions
        // Functions
        $query = $this->newQuery('Functions');
        $query->atomIs(array('Function', 'Closure', 'Arrowfunction'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
 sideEffect{ 
    lines = []; 
    reference = it.get().properties("reference").any(); 
    fullnspath = it.get().value("fullnspath"); 
    returntype = 'None'; 
    name = it.get().label();
    phpdoc = '';
}
.where( 
    __.out("BLOCK").out("EXPRESSION").emit().repeat( __.out({$this->linksDown})).times($MAX_LOOPING)
      .sideEffect{ lines.add(it.get().value("line")); }
      .fold()
 )
.where( __.out("NAME").sideEffect{ name = it.get().value("fullcode"); }.fold())
.where( __.out("PHPDOC").sideEffect{ phpdoc = it.get().value("fullcode"); }.fold())
GREMLIN
)
              ->raw(<<<'GREMLIN'
 sideEffect{ 
    file = ""; 
    namespace = "\\"; 
}
.where( 
    __.in().emit().repeat( __.inE().not(hasLabel("DEFINITION")).outV()).until(hasLabel("File"))
           .coalesce( 
                __.hasLabel("File").sideEffect{ file = it.get().value("fullcode"); },
                __.hasLabel("Namespace").sideEffect{ namespace = it.get().value("fullnspath"); }
                )
           .fold() 
)
GREMLIN
)
              ->raw(<<<'GREMLIN'
map{ ["name":name, 
      "type":it.get().label().toString().toLowerCase(),
      "line":it.get().value("line"),
      "file":file, 
      "namespace":namespace, 
      "fullnspath":fullnspath, 
      "reference":reference,
      "returntype":returntype,
      "begin": lines.min(), 
      "end":lines.max(),
      "phpdoc":phpdoc
      ]; 
}
GREMLIN
);
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $toDump = array();
        $unique = array();
        foreach($result->toArray() as $row) {
            if (isset($unique[$row['name'] . $row['line']])) {
                continue;  // Skipping double definitions until we can differentiate them.
            }
            $unique[$row['name'] . $row['line']] = 1;

            $methodIds[$row['fullnspath']] = ++$methodCount;

            $ns = preg_grep('%^' . addslashes($row['namespace']) . '$%i', array_keys($namespacesId));
            $ns = $namespacesId[array_pop($ns)];

            $toDump[] = array($methodCount,
                              $row['name'],
                              $row['type'],
                              $this->files[$row['file']],
                              $ns,
                              (int) $row['reference'],
                              $row['returntype'],
                              (int) $row['begin'],
                              (int) $row['end'],
                              $row['phpdoc'],
                              (int) $row['line'],
                              );
        }
        $total = $this->storeToDumpArray('functions', $toDump);
        display("$total functions\n");

        $query = $this->newQuery('Function parameters');
        $query->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('ARGUMENT')
              ->atomIs(array('Function', 'Closure', 'Arrowfunction'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<'GREMLIN'
where( __.sideEffect{ fonction = it.get().label().toString().toLowerCase(); 
                      fullnspath = it.get().value("fullnspath");  
                    }.fold() 
      )
.where( __.out('NAME')
         .sideEffect{ fonction = it.get().value("fullcode").toString().toLowerCase();}
         .fold()
     )
.sideEffect{ classe = it.get().value("fullnspath")}

.select('first')

.sideEffect{
    init = 'None';
    typehint = 'None';
}
.where( __.out('NAME').sideEffect{ name = it.get().value("fullcode")}.fold())
.where( __.out('TYPEHINT').sideEffect{ typehint = it.get().value("fullcode")}.fold())
.where( __.out('DEFAULT').not(where(__.in("RIGHT"))).sideEffect{ init = it.get().value("fullcode")}.fold())
.map{ 
    x = ["name": name,
         "fullnspath":fullnspath,
         "rank":it.get().value("rank"),
         "variadic":it.get().properties("variadic").any(),
         "reference":it.get().properties("reference").any(),
         "line":it.get().properties("line"),

         "function":fonction,

         "init": init,
         "typehint":typehint,
         ];
}

GREMLIN
);
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $toDump = array();
        foreach($result->toArray() as $row) {
            // Those were skipped in the previous loop
            if (!isset($methodIds[$row['fullnspath']])) {
                continue;
            }

            $toDump[] = array( '',
                               $row['name'],
                               0,
                               $methodIds[$row['fullnspath']],
                               (int) $row['rank'],
                               (int) $row['reference'],
                               (int) $row['variadic'],
                               $row['init'],
                               (int) $row['line'],
                               $row['typehint'],
            );
        }
        $total = $this->storeToDumpArray('arguments', $toDump);
        display("$total function arguments\n");
    }

    private function collectStructures_namespaces(): array {
        $query = $this->newQuery('collectStructures_namespaces');
        $query->atomIs('Namespace', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->initVariable('name', 'it.get().value("fullcode") == " " ? "\\\\" : "\\\\" + it.get().value("fullcode") + "\\\\"')
              ->getVariable('name')
              ->unique();
        $query->prepareRawQuery();
        $namespaces = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $namespaces->string2Array();

        $this->dump->storeInTable('namespaces', $namespaces);

        $namespacesId = $this->dump->fetchTable('namespaces');
        $namespacesId->map(function (array $x): array { $x['namespace'] = mb_strtolower($x['namespace']); return $x; });
        return $namespacesId->toHash('namespace', 'id');
    }

    private function collectFiles(): void {
        $this->files = $this->dump->fetchTable('files')->toHash('file', 'id');
    }

    private function collectPhpStructures(): void {
        $this->collectPhpStructures2('Functioncall', 'Functions/IsExtFunction', 'function');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Constants/IsExtConstant', 'constant');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Interfaces/IsExtInterface', 'interface');
        $this->collectPhpStructures2('Identifier", "Nsname', 'Traits/IsExtTrait', 'trait');
        $this->collectPhpStructures2('Newcall", "Identifier", "Nsname', 'Classes/IsExtClass', 'class');
    }

    private function collectPhpStructures2(string $label, string $analyzer, string $type): int {
        $query = <<<GREMLIN
g.V().hasLabel("$label").where( __.in("ANALYZED").has("analyzer", "$analyzer"))
.coalesce( __.out("NAME"), __.filter{true;})
.groupCount("m").by("fullcode").cap("m").next().sort{ it.value.toInteger() };
GREMLIN;
        $res = $this->gremlin->query($query);
        $res->deHash(array($type));

        $total = $this->dump->storeInTable('atomsCounts', $res);
        display("$total PHP {$type}s\n");

        return $total;
    }

    private function collectDefinitionsStats(): void {
        $toDump = array();
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
            $toDump[] = array('',
                              $name,
                              $res->toInt(),
                              );

            $query = <<<GREMLIN
g.V().hasLabel("$label").where(__.in("DEFINITION").not(hasLabel("Virtualproperty"))).count();
GREMLIN;
            $res = $this->gremlin->query($query);
            $toDump[] = array('',
                              "$name defined",
                              $res->toInt(),
                              );
        }

        $count = $this->storeToDumpArray('hash', $toDump);
        display("$count Definitions Stats");
    }

    private function collectFilesDependencies(): void {
        // Direct inclusion
        $query = $this->newQuery('Inclusions');
        $query->atomIs('Include', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('ARGUMENT')
              ->outIsIE('CODE')
              ->_as('include')
              ->goToInstruction('File')
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'include'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);

        // Finding extends and implements
        $query = $this->newQuery('Extensions');
        $query->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToInstruction('File')
              ->savePropertyAs('fullcode', 'calling')
              ->back('first')

              ->raw('outE().hasLabel("EXTENDS", "IMPLEMENTS").sideEffect{ type = it.get().label().toLowerCase(); }.inV()')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)

              ->goToInstruction('File')
              ->savePropertyAs('fullcode', 'called')

              ->raw('map{ ["id": "", "file":calling, "include":called, "type":type]; }');
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' extends for classes');

        // Finding extends for interfaces
        $query = $this->newQuery('Interfaces');
        $query->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)
              ->_as('classe')
              ->_as('id')
              ->_as('type')
              ->raw(<<<'GREMLIN'
repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File"))
GREMLIN
)
                ->_as('file')
              ->raw(<<<'GREMLIN'
select("classe").out("EXTENDS")
.repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'extends'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' extends for interfaces');

        // Finding typehint
        $query = $this->newQuery('Typehint');
        $query->atomIs(Analyzer::FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('ARGUMENT')
              ->outIs('TYPEHINT')
              ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
              ->inIs('DEFINITION')
              ->goToInstruction('File')
              ->_as('include')

              ->back('first')
              ->_as('id')
              ->_as('type')
              ->goToInstruction('File')
              ->_as('file')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count1 = $this->storeToDump('filesDependencies', $query);

        $query = $this->newQuery('Return Typehint');
        $query->atomIs(Analyzer::FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RETURNTYPE')
              ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
              ->inIs('DEFINITION')
              ->goToInstruction('File')
              ->_as('include')

              ->back('first')
              ->_as('id')
              ->_as('type')
              ->goToInstruction('File')
              ->_as('file')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count2 = $this->storeToDump('filesDependencies', $query);
        display(($count1 + $count2) . ' typehint ');

        // Finding trait use
        $query = $this->newQuery('Return Typehint');
        $query->atomIs('Usetrait', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('USE')
              ->_as('classe')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("classe").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' traits ');

        // traits
        $query = $this->newQuery('Return Typehint');
        $query->atomIs(array('Class', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('USE')
              ->_as('classe')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("classe").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' return types');

        // Functioncall()
        $query = $this->newQuery('Functioncall');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('functioncall')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("functioncall").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' functioncall');

        // constants
        $query = $this->newQuery('Constant');
        $query->atomIs('Identifier', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn(array('NAME', 'CLASS', 'MEMBER', 'AS', 'CONSTANT', 'TYPEHINT', 'EXTENDS', 'USE', 'IMPLEMENTS', 'INDEX'))
              ->_as('constant')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("constant").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' constants');

        // New
        $query = $this->newQuery('New');
        $query->atomIs(array('New', 'Clone'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs(array('NEW', 'CLONE'))
              ->_as('constant')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("constant").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' new and clone');

        // static calls (property, constant, method)
        $query = $this->newQuery('static calls');
        $query->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CLASS')
              ->_as('constant')
              ->raw(<<<GREMLIN
repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('file')
              ->_as('id')
              ->_as('type')
              ->raw(<<<GREMLIN
select("constant").in("DEFINITION")
.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File"))
GREMLIN
)
              ->_as('include')
              ->select(array('id'      => '',
                             'file'    => 'fullcode',
                             'include' => 'fullcode',
                             'type'    => 'use'
                             ));
        $count = $this->storeToDump('filesDependencies', $query);
        display($count . ' static call');

        // Skipping normal method/property call : They actually depends on new
        // Magic methods : todo!
        // instanceof ?
    }

    private function collectClassesDependencies(): void {
        // Finding extends and implements
        $query = $this->newQuery('Extensions of classes');
        $query->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')
              ->savePropertyAs('fullnspath', 'calling')

              ->raw('outE().hasLabel("EXTENDS", "IMPLEMENTS").sideEffect{ type = it.get().label().toLowerCase(); }.inV()')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->savePropertyAs('fullnspath', 'called')

              ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":type, 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' extends for classes');

        // Finding extends for interfaces
        $query = $this->newQuery('Interfaces extensions');
        $query->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'calling')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')

              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)

              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":"interface", 
      "type":"extends", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"interface", 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' extends for interfaces');

        // Finding typehint
        $query = $this->newQuery('Typehint');
        $query->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
              ->inIs('DEFINITION')
              ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->back('first')
              ->goToInstruction(Analyzer::CIT)

              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"typehint", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count1 = $this->storeToDump('classesDependencies', $query);

        $query = $this->newQuery('Return Typehint');
        $query->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RETURNTYPE')
              ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')
              ->back('first')

              ->goToInstruction(Analyzer::CIT)

              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"typehint", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count2 = $this->storeToDump('classesDependencies', $query);

        display(($count1 + $count2) . ' typehint ');

        // Finding trait use
        $query = $this->newQuery('Traits');
        $query->atomIs(array('Class', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')

              ->outIs('USE')
              ->outIs('USE')

              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')
              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"use", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"trait", 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' trait use ');

        // New
        $query = $this->newQuery('New');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs(array('Class'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->back('first')
              ->goToInstruction('Class') // no trait?

              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"new", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"class", 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' new ');

        // Clone
        $query = $this->newQuery('Clone');
        $query->atomIs('Clone', Analyzer::WITHOUT_CONSTANTS)
              ->goToInstruction(Analyzer::CIT)
              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')

              ->outIs('CLONE')
              ->inIs('DEFINITION')
              ->atomIs(array('Class'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'called')
              ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"clone", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' clone');

        // static calls (property, constant, method)
        $query = $this->newQuery('Static calls');
        $query->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'), Analyzer::WITHOUT_CONSTANTS)
              ->raw('sideEffect{ type = it.get().label().toLowerCase(); }')

              ->goToInstruction(Analyzer::CIT)
              ->savePropertyAs('fullnspath', 'calling')
              ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')

              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'called')
              ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":type, 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $query->prepareRawQuery();
        $count = $this->storeToDump('classesDependencies', $query);
        display($count . ' static calls CPM');

        // Skipping normal method/property call : They actually depends on new
        // Magic methods : todo!
        // instanceof ?
    }

    private function collectHashCounts($query, string $name): void {
        if ($query instanceof Query) {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $index = $result->toArray()[0];
        } else {
            $index = $this->gremlin->query($query);
            $index = $index->toArray()[0];
        }

        $toDump = array();
        foreach($index as $number => $count) {
            $toDump[] = array('',
                              $name,
                              $number,
                              $count,
                             );
        }

        if (!empty($toDump)) {
            $total = $this->storeToDumpArray('hashResults', $toDump);
        } else {
            $total = 0;
        }

        display( "$name : $total");
    }

    private function collectMissingDefinitions(): void {
        $toDump = array();

        $functioncallCount  = $this->gremlin->query('g.V().hasLabel("Functioncall").count()')[0];
        $functioncallMissed = $this->gremlin->query('g.V().hasLabel("Functioncall")
             .has("token", within("T_STRING", "T_NS_SEPARATOR"))
             .not(where(__.in("DEFINITION")))
             .not(where(__.in("ANALYZED").has("analyzer", "Functions/IsExtFunction")))
             .where( __.out("NAME").hasLabel("Identifier", "Nsname", "Name"))
        ');
        if ($functioncallMissed === 0) {
            file_put_contents("{$this->config->log_dir}/functions.missing.txt", 'Nothing found');
            $functioncallMissed = 0;
        } else {
            file_put_contents("{$this->config->log_dir}/functions.missing.txt", implode(PHP_EOL, array_column($functioncallMissed->toArray(), 'fullcode')));
            $functioncallMissed = $functioncallMissed->toInt();
        }
        $toDump[] = array('',
                          'functioncall total',
                          $functioncallCount,
                          );
        $toDump[] = array('',
                          'functioncall missed',
                          $functioncallMissed,
                          );

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
        $toDump[] = array('',
                          'methodcall total',
                          $methodCount,
                          );
        $toDump[] = array('',
                          'methodcall missed',
                          $methodMissed,
                          );

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
        $toDump[] = array('',
                          'member total',
                          $memberCount,
                          );
        $toDump[] = array('',
                          'member missed',
                          $memberMissed,
                          );

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
        $toDump[] = array('',
                          'static methodcall total',
                          $staticMethodCount,
                          );
        $toDump[] = array('',
                          'static methodcall missed',
                          $staticMethodMissed,
                          );

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
        $toDump[] = array('',
                          'static constant total',
                          $staticConstantCount,
                          );
        $toDump[] = array('',
                          'static constant missed',
                          $staticConstantMissed,
                          );

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
        $toDump[] = array('',
                          'static property total',
                          $staticPropertyCount,
                          );
        $toDump[] = array('',
                          'static property missed',
                          $staticPropertyMissed,
                          );

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
        $toDump[] = array('',
                          'constant total',
                          $constantCounts,
                          );
        $toDump[] = array('',
                          'constant missed',
                          $constantMissed,
                          );

        $this->storeToDumpArray('hash', $toDump);
    }

    private function collectMethodsCounts(): void {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Trait").groupCount("m").by( __.out("METHOD", "MAGICMETHOD").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'MethodsCounts');
    }

    private function collectPropertyCounts(): void {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Trait").groupCount("m").by( __.out("PPP").out("PPP").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassPropertyCounts');
    }

    private function collectClassTraitsCounts(): void {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class").groupCount("m").by( __.out("USE").out("USE").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassTraits');
    }

    private function collectConstantCounts(): void {
        $query = <<<'GREMLIN'
g.V().hasLabel("Class", "Trait").groupCount("m").by( __.out("CONST").out("CONST").count() ).cap("m"); 
GREMLIN;
        $this->collectHashCounts($query, 'ClassConstantCounts');
    }

    private function collectNativeCallsPerExpressions(): void {
        $MAX_LOOPING = Analyzer::MAX_LOOPING;

        $query = $this->newQuery('collectNativeCallsPerExpressions');
        $query->atomIs('Sequence', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('EXPRESSION')
              ->atomIsNot(array('Assignation', 'Case', 'Catch', 'Class', 'Classanonymous', 'Closure', 'Concatenation', 'Default', 'Dowhile', 'Finally', 'For', 'Foreach', 'Function', 'Ifthen', 'Include', 'Method', 'Namespace', 'Php', 'Return', 'Switch', 'Trait', 'Try', 'While'), Analyzer::WITHOUT_CONSTANTS)
              ->_as('results')
              ->raw(<<<GREMLIN
groupCount("m").by( __.emit( ).repeat( __.out({$this->linksDown}).not(hasLabel("Closure", "Classanonymous")) ).times($MAX_LOOPING).hasLabel("Functioncall")
      .where( __.in("ANALYZED").has("analyzer", "Functions/IsExtFunction"))
      .count()
).cap("m")
GREMLIN
);
        $query->prepareRawQuery();
        $this->collectHashCounts($query, 'NativeCallPerExpression');
    }

    private function collectGlobalVariables(): int {
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
              ->raw(<<<'GREMLIN'
map{['id': '',
     'file':path,
     'line' : it.get().value('line'),
     'variable' : it.get().value('fullcode'),
     'isRead' : 'isRead' in it.get().keys() ? 1 : 0,
     'isModified' : 'isModified' in it.get().keys() ? 1 : 0,
     'type' : type == 'Variabledefinition' ? 'implicit' : type == 'Globaldefinition' ? 'global' : '$GLOBALS'
     ];

}
GREMLIN
);
        $total = $this->storeToDump('globalVariables', $query);

        return $total;
    }

    private function collectReadability(): void {
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

        $toDump = array();
        foreach($index as $row) {
            $toDump[] = array('',
                              $row['name'],
                              $row['type'],
                              $row['total'],
                              $row['expression'],
                              $row['file'],
                             );
        }
        $total = $this->storeToDumpArray('readability', $toDump);
        display("$total readability index");
    }

    public function checkRulesets($ruleset, array $analyzers): void {
        $sqliteFile = $this->config->dump;

        $sqlite = new \Sqlite3($sqliteFile);
        $sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $query = 'SELECT analyzer FROM resultsCounts WHERE analyzer IN (' . makeList($analyzers) . ')';
        $ran = array();
        $res = $sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $ran[] = $row['analyzer'];
        }

        if (empty(array_diff($analyzers, $ran))) {
            $query = "INSERT INTO themas (\"id\", \"thema\") VALUES (null, \"$ruleset\")";
            $sqlite->query($query);
        }
    }

    private function expandRulesets(): void {
        $res = $this->dump->fetchTable('resultsCounts', array('analyzer'));
        $analyzers = $res->toList('analyzer');

        $res = $this->dump->fetchTable('themas', array('thema'));
        $ran = $res->toList('thema');

        $rulesets = $this->rulesets->listAllRulesets();
        $rulesets = array_diff($rulesets, $ran);

        $add = array();
        foreach($rulesets as $ruleset) {
            $analyzerList = $this->rulesets->getRulesetsAnalyzers(array($ruleset));

            $diff = array_diff($analyzerList, $analyzers);
            $diff = array_filter($diff, function (string $x): bool { return (substr($x, 0, 5) !== 'Dump/') && (substr($x, 0, 9) !== 'Complete/');  });
            if (empty($diff)) {
                $add[] = array('', $ruleset);
            }
        }

        if (!empty($add)) {
            $this->dump->storeInTable('themas', $add);
        }
    }

    private function newQuery(string $title): Query {
        return new Query(0, $this->config->project, $title, $this->config->executable);
    }

    public function collect(): void {
        $begin = microtime(\TIME_AS_NUMBER);
        $this->collectClassChanges();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Class Changes: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectFiles();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Files: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;

        $this->collectFilesDependencies();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Files Dependencies: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectClassesDependencies();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Classes Dependencies: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->getAtomCounts();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Atom Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;

        $this->collectPhpStructures();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Php Structures: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectStructures();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Structures: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectVariables();

        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Variables: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;

        $this->collectReadability();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Readability: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectMethodsCounts();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Method Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;

        $this->collectPropertyCounts();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Property Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectConstantCounts();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Constant Counts: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        $begin = $end;
        $this->collectNativeCallsPerExpressions();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Native Calls Per Expression: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

        $begin = $end;
        $this->collectClassTraitsCounts();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Trait counts per Class: ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

        $begin = $end;
        $this->collectDefinitionsStats();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Definitions stats : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

        $begin = microtime(\TIME_AS_NUMBER);
        $this->collectGlobalVariables();
        $end = microtime(\TIME_AS_NUMBER);
        $this->log->log( 'Collected Global Variables : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");

        // Dev only
        if ($this->config->is_phar === Config::IS_NOT_PHAR) {
            $begin = microtime(\TIME_AS_NUMBER);
            $this->collectMissingDefinitions();
            $end = microtime(\TIME_AS_NUMBER);
            $this->log->log( 'Collected Missing definitions : ' . number_format(1000 * ($end - $begin), 2) . "ms\n");
        }
    }

    private function collectClassChanges(): void {
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
map{[ "id": "",
      "type": 'Constant Value',
      "name":name,
      "parent":class2,
      "parentValue":name + " = " + default2,
      "class":class1,
      "classValue":name + " = " + default1];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

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
map{[ "id": "",
      "type": "Constant visibility",
      "name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

        $query = $this->newQuery('Method Signature');
        $query->atomIs('Method', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')
              ->raw('sideEffect{ signature1 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }')
              ->inIs('METHOD')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)

              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->raw('sideEffect{ signature2 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }.filter{ signature2 != signature1; }')
              ->raw(<<<'GREMLIN'
map{[ "id": "",
      "type": "Method Signature",
      "name":name,
      "parent":class2,
      "parentValue":"function " + name + "(" + signature2.join(", ") + ")",
      "class":class1,
      "classValue":"function " + name + "(" + signature1.join(", ") + ")"];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

         $query = $this->newQuery('Method Visibility');
         $query->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'fnp')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs(array('METHOD', 'MAGICMETHOD'))
              ->savePropertyAs('fullcode', 'name1')
              ->back('first')
              ->inIs('OVERWRITE')
              ->savePropertyAs('visibility', 'visibility2')
              ->raw('filter{visibility1  != visibility2;}')
              ->inIs('METHOD')
              ->savePropertyAs('fullcode', 'name2')
              ->raw(<<<'GREMLIN'
map{ ["id": "",
      "type": "Method Visibility",
      "name":fnp.tokenize('::')[1],
      "parent":name1,
      "parentValue":visibility2 + ' ' + fnp.tokenize('::')[1],
      "class":name2,
      "classValue":visibility1 + ' ' + fnp.tokenize('::')[1]];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

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
map{ ["id": "",
      "type": "Member Default",
      "name":name,
      "parent":class2,
      "parentValue":name + ' = ' + default2,
      "class":class1,
      "classValue":name + ' = ' + default1];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

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
map{ ["id": "",
      "type": "Member Visibility",
      "name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
);
        $total += $this->storeToDump('classChanges', $query);

        display("Found $total class changes\n");
    }

    private function storeToDump(string $table, Query $query): int {
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            return 0;
        }
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        return $this->dump->storeInTable($table, $result);
    }

    private function storeToDumpArray(string $table, array $result): int {
        return $this->dump->storeInTable($table, $result);
    }

    private function loadSqlDump(): void {
        $dumps = glob($this->config->tmp_dir . '/dump-*.php');
        display('Loading ' . count($dumps) . ' dumped SQL files');

        foreach($dumps as $dump) {
            include $dump;

            $this->dump->storeQueries($queries);
            unlink($dump);
        }
    }
}

?>