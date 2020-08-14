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


class CheckTypeWithAtom extends DSL {
    public function run(): Command {
        assert(func_num_args() === 1, 'Wrong number of argument for ' . __METHOD__);
        list($var) = func_get_args();

        assert($this->assertVariable($var, self::VARIABLE_READ));

        $gremlin = <<<GREMLIN
filter{
    if ($var == "\\\\int") {
        !((it.get().label() in ["Integer", "Addition", "Multiplication", "Bitshift", "Power"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") == "T_INT_CAST"));
    } else if ($var == "\\\\string") {
        !((it.get().label() in ["String", "Heredoc", "Concatenation"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") == "T_STRING_CAST"));
    } else if ($var == "\\\\array") {
        !((it.get().label() in ["Arrayliteral"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") == "T_ARRAY_CAST"));
    } else if ($var == "\\\\float") {
        !((it.get().label() in ["Float", "Integer"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") in ["T_DOUBLE_CAST", "T_INT_CAST"]));
    } else if ($var == "\\\\bool") {
        !((it.get().label() in ["Boolean", "Comparison"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") == "T_BOOL_CAST"));
    } else if ($var == "\\\\object") {
        !((it.get().label() in ["Variable", "New", "Clone"]) ||
           (it.get().label() == "Cast" &&  it.get().value("token") == "T_OBJECT_CAST"));
    } else if ($var == "\\\\void") {
        !(it.get().label() in ["Void"]);
    } else if ($var == "\\\\callable") {
        !(it.get().label() in ["Closure", "Arrowfunction"]);
    } else if ($var == "\\\\iterable") {
        if (it.get().label() in ["Arrayliteral"]) {
            false;
        } else if("fullnspath" in it.get().properties() && it.get().value("fullnspath") in ["\\\\arrayobject", "\\\\iterator"]) {
            false;
        } else {
            true;
        }
    } else {
        true;
    }
}
GREMLIN;
        return new Command($gremlin);
    }
}
?>
