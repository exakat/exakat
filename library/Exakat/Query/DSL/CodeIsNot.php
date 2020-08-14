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

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;
use Exakat\Data\Dictionary;

class CodeIsNot extends DSL {
    public function run(): Command {
        switch(func_num_args()) {
            case 1 :
                $code = func_get_arg(0);
                $translate = Analyzer::TRANSLATE;
                $caseSensitive = Analyzer::CASE_INSENSITIVE;
                break;

            case 2:
                $code = func_get_arg(0);
                $translate = func_get_arg(1);
                $caseSensitive = Analyzer::CASE_INSENSITIVE;
                break;

            default:
            case 3:
                list($code,$translate, $caseSensitive) = func_get_args();
        }

        if (is_array($code) && empty($code)) {
            return new Command(Query::NO_QUERY);
        }

        $col = $caseSensitive === Analyzer::CASE_INSENSITIVE ? 'lccode' : 'code';

        if ($translate === Analyzer::TRANSLATE) {
            $translatedCode = array();
            $code = makeArray($code);
            $translatedCode = $this->dictCode->translate($code, $caseSensitive === Analyzer::CASE_INSENSITIVE ? Dictionary::CASE_INSENSITIVE : Dictionary::CASE_SENSITIVE);

            if (empty($translatedCode)) {
                // Couldn't find anything in the dictionary : OK!
                return new Command(Query::NO_QUERY);
            }

            return new Command("not(has(\"$col\", within(***)))", array($translatedCode));
        } else {
            return new Command("not(has(\"$col\", within(***)))", array(makeArray($code)));
        }
    }
}
?>
