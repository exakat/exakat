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

use Exakat\Query\DSL\DSL;
use Exakat\Query\DSL\DSLFactory;
use Exakat\Query\DSL\Command;

class Query {
    const STOP_QUERY = 'filter{ false; }';
    const NO_QUERY   = 'filter{ true;  }';

    const TO_GREMLIN = true;
    const NO_GREMLIN = false;
    
    private $analyzerId = null;
    private $id         = null;
    private $project    = null;
    private $analyzer   = null;
    private $php        = null;

    private $commands         = array();
    private $arguments        = array();
    private $query            = null;
    private $queryFactory     = null;
    private $sides            = array();
    
    public function __construct($id, $project, $analyzer, $php, $datastore) {
        $this->id       = $id;
        $this->project  = $project;
        $this->analyzer = $analyzer;
        $this->php      = $php;
        
        $this->queryFactory = new DSLFactory($datastore);
    }

    public function __call($name, $args) {
        try {
            $command = $this->queryFactory->factory($name);
            $this->commands[] = $command->run(...$args);
        } catch (UnknownDsl $e) {
            die('This is an unknown DSL');
        }
        
        return $this;
    }
    
    public function side() {
        $this->sides[] = $this->commands;
        $this->commands = array();
        
        return $this;
    }

    public function prepareSide() {
        $commands = array_column($this->commands, 'gremlin');
        
        assert(!empty($this->sides), 'No side was started! Missing $this->side() ? ');
        assert(!empty($commands), 'No command in side query');

        $query = '__.'.implode(".\n", $commands);
        $args = array_column($this->commands, 'arguments');
        $args = array_merge(...$args);

        $query = str_replace(array_keys($args), '***', $query);

        $sack = $this->prepareSack($this->commands, self::NO_GREMLIN);

        $return = new Command("where( $query )", array_values($args));
        $return->setSack($sack);

        $this->commands = array_pop($this->sides);
        return $return;
    }

    public function prepareQuery($analyzerId) {
        assert($this->query === null, 'query is already ready');
        assert(empty($this->sides), 'sides are not empty : left '.count($this->sides).' element');

        // @doc This is when the object is a placeholder for others.
        if (empty($this->commands)) {
            return true;
        }
        
        $this->analyzerId = $analyzerId;

        $sack = $this->prepareSack($this->commands);

        $commands = array_column($this->commands, 'gremlin');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            // any 'stop_query' is blocking
            return $this->query = '';
        }

        if (substr($commands[0], 0, 9) === 'hasLabel(') {
            $first = $commands[0];
            array_shift($commands);
            $this->query = "g{$sack}.V().$first.groupCount(\"processed\").by(count()).as(\"first\")";
            if (!empty($commands)) {
                $this->query .= '.'.implode(".\n", $commands);
            }
        } elseif (substr($commands[0], 0, 39) === 'where( __.in("ANALYZED").has("analyzer"') {
            array_shift($commands);
            $arg0 = array_pop($this->commands[0]->arguments);
            unset($this->commands[0]);
            $this->query = 'g'.$sack.'.V().hasLabel("Analysis").has("analyzer", within('.makeList($arg0).')).out("ANALYZED").as("first").groupCount("processed").by(count())';
            if (!empty($commands)) {
                $this->query .= '.'.implode(".\n", $commands);
            }
        } else {
            assert(false, 'No optimization : gremlin query in analyzer should have use g.V. ! '.$commands[1]);
        }

        $arguments = array_column($this->commands, 'arguments');
        if (empty($arguments)) {
            $this->arguments = array();
        } else {
            $this->arguments = array_merge(...$arguments);
        }

        // search what ? All ?
        $this->query = <<<GREMLIN

{$this->query}

.dedup().groupCount("total").by(count()).addE("ANALYZED").from(g.V({$this->analyzerId})).cap("processed", "total")

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v

GREMLIN;
//        assert(!empty($this->analyzerId), "The analyzer Id for {$this->analyzerId} wasn't set. Can't save results.");
        
        return true;
    }
    
    public function prepareRawQuery() {
        $commands = array_column($this->commands, 'gremlin');
        $commands = implode('.', $commands);
        $this->arguments = array_merge(...array_column($this->commands, 'arguments'));

        $sack = $this->prepareSack($this->commands);

        $this->query = <<<GREMLIN
g{$sack}.V().as('first').$commands

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v\n

GREMLIN;

    }
    
    public function printRawQuery() {
        $this->prepareRawQuery();

        print $this->query.PHP_EOL;
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
        $this->prepareQuery($this->analyzerId);
        
        var_dump($this->query);
        print_r($this->arguments);
        die(__METHOD__);
    }

    private function prepareSack($commands, $toGremlin = self::TO_GREMLIN) {
        foreach($commands as $command) {
            if ($command->getSack() !== null) {
                if ($toGremlin === self::TO_GREMLIN) {
                    return '.withSack{'.$command->getSack().'}{it.clone()}';
                } else {
                    return $command->getSack();
                }
            }
        }
        
        return null;
    }
}
?>
