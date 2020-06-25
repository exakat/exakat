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

class Select extends DSL {
    public function run(): Command {
        list($values) = func_get_args();

        $by     = array();
        $select = array();
        foreach($values as $k => $v) {
            assert(in_array($k, $this->availableLabels, \STRICT_COMPARISON), "No such step as '$k'");

            if (is_int($k)) {
                $select[] = "by(constant($v))";
            } elseif ($v === 'id') {
                $select[] = $k;
                $by[]     = 'by(id())';
            } elseif (in_array($v, self::PROPERTIES, \STRICT_COMPARISON)) {
                // Use a local property
                $select[] = $k;
                $by[]     = "by(\"$v\")";
            } elseif (substr(trim($v), 0, 2) === '__') {
                // __.out('BLOCK').count()
                $select[] = $k;
                $by[]     = "by($v)";
            } else {
                // Turn value into a constant
                $select[] = $k;
                $by[]     = "by(constant(\"$v\"))";
            }
        }

        if (empty($by)) {
            $command = 'select(' . makeList($select) . ')';
        } else {
            $command = 'select(' . makeList($select) . ').' . implode('.', $by);
        }

        return new Command($command);
    }
}
?>
