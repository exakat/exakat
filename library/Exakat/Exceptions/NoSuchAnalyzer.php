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


namespace Exakat\Exceptions;


class NoSuchAnalyzer extends \RuntimeException {
    public function __construct($analyzer, $themes) {
        $die = "Couldn't find '$analyzer'. Aborting\n";

        if (preg_match('#[a-z0-9_]+/[a-z0-9_]+$#i', $analyzer) === 0) {
            $die .= "Analyzers use the format Folder/Rule, for example Structures/UselessInstructions. Check the documentation http://exakat.readthedocs.io/\n";
        } else {
            $r = $themes->getSuggestionClass($analyzer);
            if (empty($r)) {
                $die .= "Couldn't find a suggestion. Check the documentation http://exakat.readthedocs.io/\n";
            } else {
                $die .= 'Did you mean : ' . str_replace('\\', '/', implode(', ', array_slice($r, 0, 5)));
                if (count($r) > 5) {
                    $die .= ' (' . (count($r) - 5) . ' more possible)';
                }
                $die .= "\n";
            }
        }

        parent::__construct($die);
    }
}

?>