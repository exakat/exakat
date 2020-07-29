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

declare(strict_types = 1);

namespace Exakat\Query;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\DSL\DSLFactory;
use Exakat\Query\DSL\Command;
use Exakat\Project;

class Query {
    public const STOP_QUERY = 'filter{ false; }';
    public const NO_QUERY   = 'filter{ true;  }';

    const TO_GREMLIN = true;
    const NO_GREMLIN = false;

    const QUERY_RUNNING = true;
    const QUERY_STOPPED = false;

    private const SACK = '.withSack(["m":[], "processed":0, "total":0])';

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

    public function __construct(int $id, Project $project, string $analyzer, string $php, ?array $dependsOn = array()) {
        $this->id        = $id;
        $this->project   = $project;
        $this->analyzer  = $analyzer;
        $this->php       = $php;

        $this->queryFactory = new DSLFactory($analyzer, $dependsOn);
    }

    public function __call(string $name, array $args): self {
        if ($this->stopped === self::QUERY_STOPPED) {
            return $this;
        }

        assert(!(empty($this->commands) && empty($this->sides)) || in_array(strtolower($name), array('atomis', 'analyzeris', 'atomfunctionis')), "First step in Query must be atomIs, atomFunctionIs or analyzerIs ($name used)");

        $command = $this->queryFactory->factory($name);
        if (in_array($name, array('not', 'filter', 'optional'))) {
            $chain = $this->prepareSide();
            $last = $command->run($chain);
        } else {
            $last = $command->run(...$args);
        }
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
                    $this->raw('sack{m,v -> ++m["processed"]; m;}');
                    break;

                case 'analyzeris' :
                    $this->atomIs('Analysis', Analyzer::WITHOUT_CONSTANTS);
                    $this->commands = array($this->commands[1]);

                    $this->propertyIs('analyzer', $args[0], Analyzer::CASE_SENSITIVE);
                    $this->outIs('ANALYZED');
                    $this->_as('first');
                    $this->raw('sack{m,v -> ++m["processed"]; m;}');

                    $this->raw('groupCount("processed").by(count())');

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

    public function side(): self {
        if ($this->stopped === self::QUERY_STOPPED) {
            return $this;
        }

        $this->sides[] = $this->commands;
        $this->commands = array();

        return $this;
    }

    public function prepareSide(): Command {
        if ($this->stopped === self::QUERY_STOPPED) {
            return new Command(self::NO_QUERY);
        }

        $commands = array_column($this->commands, 'gremlin');

        assert(!empty($this->sides), 'No side was started! Missing $this->side() ? ');
        assert(!empty($commands), 'No command in side query');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            $this->commands = array_pop($this->sides);
            return new Command(self::STOP_QUERY);
        }

        $query = '__.' . implode(".\n", $commands);
        $args = array_column($this->commands, 'arguments');
        $args = array_merge(...$args);

        $query = str_replace(array_keys($args), '***', $query);

        $sack = $this->prepareSack($this->commands);

        $return = new Command($query, array_values($args));
        $return->setSack($sack);

        $this->commands = array_pop($this->sides);

        return $return;
    }

    public function prepareQuery(): bool {
        if ($this->stopped === self::QUERY_STOPPED) {
            return true;
        }

        assert($this->query === null, 'query is already ready');
        assert(empty($this->sides), 'sides are not empty : left ' . count($this->sides) . ' element');

        // @doc This is when the object is a placeholder for others.
        if (empty($this->commands)) {
            return true;
        }

        $sack = self::SACK;
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

    public function prepareRawQuery(): void {
        if ($this->stopped === self::QUERY_STOPPED) {
            return;
        }

        $commands = array_column($this->commands, 'gremlin');
        $arguments = array_column($this->commands, 'arguments');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            // any 'stop_query' is blocking
            $this->query = "// Query with STOP_QUERY\n";
            return ;
        }

        foreach($commands as $id => $command) {
            if ($command === self::NO_QUERY) {
                unset($commands[$id], $arguments[$id]);
            }
        }

        $commands = implode('.', $commands);
        $this->arguments = array_merge(...$arguments);

        $sack = self::SACK;

        $this->query = <<<GREMLIN
g{$sack}.V().as('first').$commands

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v\n

GREMLIN;

    }

    public function printRawQuery(): void {
        $this->prepareRawQuery();

        print $this->query . PHP_EOL;
        print_r($this->arguments);
        die(__METHOD__);
    }

    public function getQuery(): string {
        assert($this->query !== null, 'Null Query found!');
        return $this->query;
    }

    public function getArguments(): array {
        return $this->arguments;
    }

    public function printQuery(): void {
        $this->prepareQuery();

        var_dump($this->query);
        print_r($this->arguments);
        die(__METHOD__);
    }

    private function prepareSack(array $commands) {
        foreach($commands as $command) {
            if ($command->getSack() === Command::SACK_NONE) {
                continue;
            }

            return $command->getSack();
        }

        return Command::SACK_NONE;
    }

    private function sackToGremlin(array $sack): string {
        if (empty($sack)) {
            return '';
        }

        $return = array();
        foreach($sack as $name => $init) {
            $return[] = "\"$name\":" . trim((string) $init, ' {}');
        }

        $return = '.withSack{[' . implode(', ', $return) . ']}';
        return $return;
    }

    public function canSkip(): bool {
        return $this->stopped !== self::QUERY_RUNNING;
    }
}
?>