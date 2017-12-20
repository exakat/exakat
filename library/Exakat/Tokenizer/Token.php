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
    static public $ATOMS = array('Abstract', 'Addition', 'Array', 'Arrayliteral', 'Arrayappend', 'Assignation', 'As', 'Bitshift', 'Block', 'Boolean', 'Break', 'Case', 'Cast', 'Catch', 'Class',  'Classanonymous', 'Clone', 'Closure', 'Comparison', 'Coalesce', 'Concatenation', 'Const', 'Constant', 'Continue', 'Declare', 'Default', 'Defineconstant', 'Dowhile', 'Empty', 'Exit', 'File', 'Final', 'Finally', 'For', 'Foreach', 'Function', 'Functioncall', 'Goto', 'Halt', 'Heredoc', 'Identifier', 'Ifthen', 'Include', 'Inlinehtml', 'Integer', 'Instanceof', 'Insteadof', 'Interface', 'Isset', 'Global', 'Globaldefinition', 'Gotolabel', 'Keyvalue', 'List', 'Logical', 'Magicconstant', 'Member', 'Method', 'Methodcall', 'Methodcallname', 'Multiplication', 'Name', 'Namespace', 'New', 'Newcall', 'Noscream', 'Not', 'Nsname', 'Null', 'Parent', 'Parenthesis', 'Php', 'Phpvariable', 'Postplusplus', 'Preplusplus', 'Ppp', 'Private', 'Project', 'Protected', 'Propertydefinition', 'Power', 'Public', 'Real', 'Return', 'Self', 'Sequence', 'Shell', 'Sign', 'Static', 'Staticdefinition', 'Staticclass', 'Staticconstant', 'Staticmethodcall', 'Staticproperty', 'String', 'Switch', 'Ternary', 'This', 'Throw', 'Trait', 'Try', 'Unset', 'Use', 'Var', 'Variable', 'Variableobject', 'Variablearray', 'Void', 'While', 'Yield', 'Yieldfrom');
    static public $ATOMS_EXAKAT = array('Analysis', 'Noresult');
    
    static public $LINKS = array('ABSTRACT', 'APPEND', 'ARGUMENT', 'AS', 'AT', 'BLOCK', 'BREAK', 'CASE', 'CASES', 'CAST', 'CATCH', 'CLASS', 'CLONE', 'CODE', 'CONCAT', 'CONDITION', 'CONST', 'CONSTANT', 'CONTINUE', 'DEFAULT', 'EXPRESSION', 'ELSE', 'EXTENDS', 'FILE', 'FINAL', 'FINALLY', 'FUNCTION', 'GLOBAL', 'GOTO', 'GROUPUSE', 'IMPLEMENTS', 'INCREMENT', 'INDEX', 'INIT', 'INSTEADOF', 'GOTOLABEL', 'LEFT', 'METHOD', 'NAME', 'NEW', 'NOT', 'NULLABLE', 'OBJECT', 'PPP', 'POSTPLUSPLUS', 'PREPLUSPLUS', 'PRIVATE', 'PROJECT', 'MEMBER', 'PROTECTED', 'PUBLIC', 'RETURN', 'RETURNTYPE', 'RIGHT', 'SIGN', 'SOURCE', 'STATIC', 'THEN', 'THROW', 'TRAILING', 'TYPEHINT', 'USE', 'VALUE', 'VAR', 'VARIABLE', 'YIELD', 'ALIAS');
    static public $LINKS_EXAKAT = array('DEFINITION', 'ANALYZED');
    
    static public function linksAsList() {
        return '"'.implode('", "', self::$LINKS).'"';
    }
}

?>
