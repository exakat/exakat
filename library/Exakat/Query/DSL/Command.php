<?php declare(strict_types = 1);
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


namespace Exakat\Query\DSL;

class Command {
    public const SACK_NONE    = '';
    public const SACK_ARRAY   = '{ [] }{it.clone()}';
    public const SACK_HASH    = '{ [:] }{it.clone()}';
    public const SACK_INTEGER = '{ 0 }';

    private static $id = 0;
    public $gremlin    = '';
    public $arguments  = array();
    private $sack      = self::SACK_NONE;

    public function __construct(string $command, array $args = array()) {
        $c = substr_count($command, '***');

        assert(is_array($args), "Args is not an array : ($command)." . print_r($args, true));
        assert($c === count($args), "Wrong number of arguments for Command : $c placeholders, " . count($args) . " provided. ($command)\n" . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true));

        $arguments = array();
        foreach($args as $arg) {
            ++self::$id;
            $arguments['arg' . self::$id] = $arg;
        }

        $command = str_replace(array('%', '***'), array('%%', '%s'), $command);
        $command = sprintf($command, ...array_keys($arguments));

        $this->gremlin = $command;
        $this->arguments = $arguments;
    }

    public function setSack(string $default = self::SACK_NONE): void {
        assert(in_array($default, array(self::SACK_NONE,
                                        self::SACK_ARRAY,
                                        self::SACK_HASH,
                                        self::SACK_INTEGER,
                                        ), \STRICT_COMPARISON),
              'Sack must be one of the allowed constant : "' . $default . '" provided');

        $this->sack = $default;
    }

    public function getSack(): string {
        return $this->sack;
    }

    public function add(self $other): self {
        $this->gremlin   .= ".{$other->gremlin}";
        $this->arguments += $other->arguments;

        return $this;
    }
}
?>
