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


class QueryDoc {
    private $stopped = null;
    private $commands = null;
    private $arguments = null;
    private $query = null;
    private $stats = array();

    private $steps = array();

    private $cursor    = 1;
    private $cursors   = array();
    private $nodes     = array(1=> 'root');
    private $links     = array();
    private $labels    = array('first' => 1, );

    public function __construct() {    }

    public function __call($name, $args) {
        if (in_array($name, array('not', 'filter', 'optional'))) {
            $chain = $this->prepareSide();
            $this->steps[] = $name . '[ ' . $chain . ' ]';
            print "$name\n";

            $next = array_pop($this->head);
            $this->nodes[$next] = "Node $name";
            $this->cursor = array_pop($this->cursors);

            $this->links[] = array($this->cursor, $next, $name);
            $this->cursor = $next;
        } elseif (in_array($name, array('back'))) {
            $this->steps[] = $name;
            print "$name\n";

            $this->cursor = $this->labels[$args[0]];
        } elseif (in_array($name, array('as'))) {
            $this->steps[] = $name;
            print "$name\n";

            $this->labels[$args[0]] = $this->cursor;
        } else {
            $this->steps[] = $name;
            print "$name\n";

            $this->nodes[] = "Node $name";
            $next = count($this->nodes);
            $this->links[] = array($this->cursor, $next, $name);
            $this->cursor = $next;
        }


        $this->stats[$name] = ($this->stats[$name] ?? 0) + 1;

    }

    public function side(): self {
        $this->sides[] = $this->steps;
        $this->steps = array('side');

        $this->cursors[] = $this->cursor;
        $this->nodes[] = 'Node SIDE';
        $next = count($this->nodes);
        $this->head[]    = $next;
        $this->cursor    = $next;

        $this->stats['side'] = ($this->stats['side'] ?? 0) + 1;

        print "  Side\n";

        return $this;
    }

    public function prepareSide() {
        print "  prepareSide\n";
        $chain = implode('-', $this->steps);
        $this->steps = array_pop($this->sides);

        return $chain;
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

        /*
        Sack is ignored ATM
        $sack = $this->prepareSack($this->commands);
        if (is_array($sack)) {
            $sack['processed'] = 0;
            $sack['total'] = 0;
        } else {
            $sack = array('processed' => 0,
                          'total' => 0,
                          );
        }
        $sack = $this->sackToGremlin($sack);
        */
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

        $sack = self::SACK;

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

    public function display(): void {
        print '(' . implode('-', $this->steps) . ')';

//        print_r($this->stats);
        $graph = array();

        foreach($this->nodes as $id => $node) {
            $graph[] = "$id [label=\"$node\"];\n";
        }

        foreach($this->links as list($a, $b, $label)) {
            $graph[] = "$a -> $b [label = \"$label\"];\n";
        }

        file_put_contents('/tmp/docs.dot', 'digraph{ ' . implode('', $graph) . '}');
    }
}
?>