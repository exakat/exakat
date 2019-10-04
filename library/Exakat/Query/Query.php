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


namespace Exakat\Query;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\DSL\DSL;
use Exakat\Query\DSL\DSLFactory;
use Exakat\Query\DSL\Command;
use Exakat\Exceptions\UnknownDsl;

class Query {
    public const STOP_QUERY = 'filter{ false; }';
    public const NO_QUERY   = 'filter{ true;  }';

    const TO_GREMLIN = true;
    const NO_GREMLIN = false;

    const QUERY_RUNNING = true;
    const QUERY_STOPPED = false;

    private $id         = null;
    private $project    = null;
    private $analyzer   = null;
    private $php        = null;

    private $commands         = array();
    private $arguments        = array();
    private $query            = null;
    private $queryFactory     = null;
    private $sides            = array();
    private $stopped          = self::QUERY_RUNNING;

    public function __construct($id, $project, $analyzer, $php, $datastore) {
        $this->id       = $id;
        $this->project  = $project;
        $this->analyzer = $analyzer;
        $this->php      = $php;
        
        $this->queryFactory = new DSLFactory($datastore);
    }

    public function __call($name, $args) {
        if ($this->stopped === self::QUERY_STOPPED) {
            return $this;
        }

        assert(!(empty($this->commands) && empty($this->sides)) || in_array(strtolower($name), array('atomis', 'analyzeris', 'atomfunctionis')), "First step in Query must be atomIs, atomFunctionIs or analyzerIs ($name used)");

        $command = $this->queryFactory->factory($name);
        $last = $command->run(...$args);
        $this->commands[] = $last;

        if ($last->gremlin === self::STOP_QUERY && empty($this->sides)) {
            $this->query = "// Query with STOP_QUERY\n";
            $this->commands = array();

            $this->stopped = self::QUERY_STOPPED;
            
            return $this;
        }

        if (count($this->commands) === 1 && empty($this->sides)) {
            switch(strtolower($name)) {
                case 'atomis' :
                case 'atomfunctionis' :
                    $this->_as('first');
                    $this->raw('groupCount("processed").by(count())', array(), array());
                    break;

                case 'analyzeris' :
                    $this->atomIs('Analysis', Analyzer::WITHOUT_CONSTANTS);
                    $this->commands = array($this->commands[1]);

                    $this->propertyIs('analyzer', $args[0], Analyzer::CASE_SENSITIVE);
                    $this->outIs('ANALYZED');
                    $this->_as('first');

                    $this->raw('groupCount("processed").by(count())', array(), array());

                    break;

                default :
                    if ($this->commands[0]->gremlin === self::STOP_QUERY) {
                        $this->_as('first');
                        // Keep going
                    } else {
                        assert(false, 'No gremlin optimization : gremlin query "' . $name . '" in analyzer should have use g.V. ! ' . $this->commands[0]->gremlin);
                    }
            }
        }

        return $this;
    }
    
    public function side() {
        if ($this->stopped === self::QUERY_STOPPED) {
            return $this;
        }

        $this->sides[] = $this->commands;
        $this->commands = array();
        
        return $this;
    }

    public function prepareSide() {
        if ($this->stopped === self::QUERY_STOPPED) {
            return $this;
        }

        $commands = array_column($this->commands, 'gremlin');

        assert(!empty($this->sides), 'No side was started! Missing $this->side() ? ');
        assert(!empty($commands), 'No command in side query');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            $this->commands = array_pop($this->sides);
            return new Command(Query::STOP_QUERY);
        }

        $query = '__.' . implode(".\n", $commands);
        $args = array_column($this->commands, 'arguments');
        $args = array_merge(...$args);

        $query = str_replace(array_keys($args), '***', $query);

        $sack = $this->prepareSack($this->commands, self::NO_GREMLIN);

        $return = new Command($query, array_values($args));
        $return->setSack($sack);

        $this->commands = array_pop($this->sides);

        return $return;
    }

    public function prepareQuery() {
        if ($this->stopped === self::QUERY_STOPPED) {
            return true;
        }

        assert($this->query === null, 'query is already ready');
        assert(empty($this->sides), 'sides are not empty : left ' . count($this->sides) . ' element');

        // @doc This is when the object is a placeholder for others.
        if (empty($this->commands)) {
            return true;
        }

        $sack = $this->prepareSack($this->commands);
        $this->query = "g{$sack}.V()";

        $commands  = array_column($this->commands, 'gremlin');
        $arguments = array_column($this->commands, 'arguments');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            // any 'stop_query' is blocking
            $this->query = '';
            return false;
        }

        foreach($commands as $id => $command) {
            if ($command === self::NO_QUERY) {
                unset($commands[$id], $arguments[$id]);
            }
        }

        $this->query .= '.' . implode(".\n", $commands);

        if (empty($arguments)) {
            $this->arguments = array();
        } else {
            $this->arguments = array_merge(...$arguments);
        }

        return true;
    }
    
    public function prepareRawQuery() {
        if ($this->stopped === self::QUERY_STOPPED) {
            return true;
        }

        $commands = array_column($this->commands, 'gremlin');
        $arguments = array_column($this->commands, 'arguments');
        
        if (in_array(self::STOP_QUERY, $commands) !== false) {
            // any 'stop_query' is blocking
            return $this->query = "// Query with STOP_QUERY\n";
        }

        foreach($commands as $id => $command) {
            if ($command === self::NO_QUERY) {
                unset($commands[$id], $arguments[$id]);
            }
        }

        $commands = implode('.', $commands);
        $this->arguments = array_merge(...$arguments);

        $sack = $this->prepareSack($this->commands);

        $this->query = <<<GREMLIN
g{$sack}.V().as('first').$commands

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v\n

GREMLIN;

    }
    
    public function printRawQuery() {
        $this->prepareRawQuery();

        print $this->query . PHP_EOL;
        print_r($this->arguments);
        die(__METHOD__);
    }

    public function getQuery() {
        assert($this->query !== null, 'Null Query found!');
        return $this->query;
    }

    public function getArguments() {
        return $this->arguments;
    }

    public function printQuery() {
        $this->prepareQuery();
        
        var_dump($this->query);
        print_r($this->arguments);
        die(__METHOD__);
    }

    private function prepareSack($commands, $toGremlin = self::TO_GREMLIN) {
        foreach($commands as $command) {
            if ($command->getSack() === Command::SACK_NONE) {
                continue;
            }

            if ($toGremlin === self::TO_GREMLIN) {
                return '.withSack' . $command->getSack();
            } else {
                return $command->getSack();
            }
        }

        return Command::SACK_NONE;
    }
    
    public function canSkip() : bool {
        return $this->stopped !== self::QUERY_RUNNING;
    }
}
?>
