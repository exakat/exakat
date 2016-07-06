<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tokenizer;

abstract class Token {
    const ATOMS = array('Break', 'Continue', 'Arguments', 'Array', 'Arrayappend', 'Block', 'Boolean', 'Case', 'Catch', 'Class', 'Const', 'Declare', 'Default', 'Dowhile', 'File', 'Finally', 'For', 'Foreach', 'Function', 'Functioncall', 'Global', 'Heredoc', 'Identifier', 'Ifthen', 'Interface', 'Label', 'Methodcall', 'Namespace', 'Nsname', 'Null', 'Parenthesis', 'Php', 'PostPlusPlus', 'Project', 'Property', 'Return', 'Sequence', 'Shell', 'Sign', 'Staticconstant', 'Staticmethodcall', 'Staticproperty', 'String', 'Switch', 'Ternary', 'Trait', 'Try', 'Use', 'Variable', 'Void', 'While', 'Yield');
    
    const LINKS = array('ABSTRACT', 'APPEND', 'ARGUMENT', 'ARGUMENTS', 'AS', 'AT', 'BLOCK', 'BREAK', 'CASE', 'CASES', 'CAST', 'CATCH', 'CLASS', 'CLONE', 'CODE', 'CONCAT', 'CONDITION', 'CONST', 'CONSTANT', 'CONTINUE', 'DECLARE', 'ELEMENT', 'ELSE', 'EXTENDS', 'FILE', 'FINAL', 'FINALLY', 'FUNCTION', 'GLOBAL', 'GOTO', 'GROUPUSE', 'IMPLEMENTS', 'INCREMENT', 'INDEX', 'INIT', 'KEY', 'LABEL', 'LEFT', 'METHOD', 'NAME', 'NEW', 'NOT', 'OBJECT', 'PPP', 'POSTPLUSPLUS', 'PREPLUSPLUS', 'PRIVATE', 'PROJECT', 'PROPERTY', 'PROTECTED', 'PUBLIC', 'RETURN', 'RETURNTYPE', 'RIGHT', 'SIGN', 'SOURCE', 'STATIC', 'SUBNAME', 'THEN', 'THROW', 'TYPEHINT', 'USE', 'VALUE', 'VAR', 'VARIABLE', 'YIELD');
    
    static function linksAsList() {
        return '"'.join('", "', \Tokenizer\Token::LINKS).'"';
    }
}

?>
