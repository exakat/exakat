<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Tokenizer;

abstract class Token {
    static public $ATOMS = array('Arguments', 'Array', 'Arrayappend', 'As', 'Block', 'Boolean', 'Break', 'Case', 'Cast', 'Catch', 'Class', 'Clone', 'Concatenation', 'Const', 'Constant', 'Continue', 'Declare', 'Default', 'Dowhile', 'File', 'Finally', 'For', 'Foreach', 'Function', 'Functioncall', 'Goto', 'Halt', 'Heredoc', 'Identifier', 'Ifthen', 'Include', 'Instanceof', 'Interface', 'Gotolabel', 'Method', 'Methodcall', 'Namespace', 'New', 'Noscream', 'Not', 'Nsname', 'Null', 'Parenthesis', 'Php', 'Postplusplus', 'Preplusplus', 'Project', 'Member', 'Return', 'Sequence', 'Shell', 'Sign', 'Staticclass', 'Staticconstant', 'Staticmethodcall', 'Staticproperty', 'String', 'Switch', 'Ternary', 'Throw', 'Trait', 'Try', 'Use', 'Variable', 'Void', 'While', 'Yield', 'Yieldfrom');
    
    static public $LINKS = array('ABSTRACT', 'APPEND', 'ARGUMENT', 'ARGUMENTS', 'AS', 'AT', 'BLOCK', 'BREAK', 'CASE', 'CASES', 'CAST', 'CATCH', 'CLASS', 'CLONE', 'CODE', 'CONCAT', 'CONDITION', 'CONST', 'CONSTANT', 'CONTINUE', 'DECLARE', 'DEFAULT', 'EXPRESSION', 'ELSE', 'EXTENDS', 'FILE', 'FINAL', 'FINALLY', 'FUNCTION', 'GLOBAL', 'GOTO', 'GROUPUSE', 'IMPLEMENTS', 'INCREMENT', 'INDEX', 'INIT', 'GOTOLABEL', 'LEFT', 'METHOD', 'NAME', 'NEW', 'NOT', 'OBJECT', 'PPP', 'POSTPLUSPLUS', 'PREPLUSPLUS', 'PRIVATE', 'PROJECT', 'MEMBER', 'PROTECTED', 'PUBLIC', 'RETURN', 'RETURNTYPE', 'RIGHT', 'SIGN', 'SOURCE', 'STATIC', 'THEN', 'THROW', 'TYPEHINT', 'USE', 'VALUE', 'VAR', 'VARIABLE', 'YIELD', 'ALIAS');
    
    static public function linksAsList() {
        return '"'.implode('", "', self::$LINKS).'"';
    }
}

?>
