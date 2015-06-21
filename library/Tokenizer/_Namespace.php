<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');
    static public $atom = 'Namespace';

    public function _check() {
        // namespace A ; namespace (empty namespace)
        $this->conditions = array(0 => array('token'  => _Namespace::$operators,
                                             'atom'   => 'none'),
                                  1 => array('atom'   => array('Identifier', 'Nsname')),
                                  2 => array('token'  => 'T_SEMICOLON'),
                                  3 => array('token'  => _Namespace::$operators),
        );
        
        $this->actions = array('insertCurlyVoid'  => 1,
                               'keepIndexed'      => true);
        $this->checkAuto();

        // namespace {}
        $this->conditions = array(0 => array('token'  => _Namespace::$operators,
                                             'atom'   => 'none'),
                                  1 => array('token'  => 'T_OPEN_CURLY'),
        );
        
        $this->actions = array('insertGlobalNs' => 1,
                               'keepIndexed'    => true);
        $this->checkAuto();

        // namespace myproject {}
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_OPEN_CURLY'),
                                  3 => array('atom'  => array('Sequence', 'Void')),
                                  4 => array('token' => 'T_CLOSE_CURLY'),
                                  5 => array('token' => array('T_NAMESPACE', 'T_CLOSE_TAG', 'T_END', 'T_SEMICOLON')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE',
                                                        2 => 'DROP',
                                                        3 => 'BLOCK',
                                                        4 => 'DROP',
                                                        ),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'addAlwaysSemicolon' => 'it');
        $this->checkAuto();

        // namespace myproject ? >
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON'),
                                  3 => array('token' => array('T_END', 'T_CLOSE_TAG')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE',
                                                        2 => 'DROP',
                                                        ),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'addAlwaysSemicolon' => 'it');
        $this->checkAuto();
        
        // namespace A; atom ;  ? >
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON',
                                             'atom'  => 'none'),
                                  3 => array('atom'  => 'yes',
                                             'notAtom' => 'Sequence'),
                                  4 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG', 'T_END'))
        );
        
        $this->actions = array('insertNsSeq'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        // namespace A; <Sequence> ? >
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON'),
                                  3 => array('atom'  => 'Sequence'),
                                  4 => array('token' => array('T_CLOSE_TAG', 'T_END'))
        );
        
        $this->actions = array('insertNs'     => true,
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        // namespace\Another : using namespace to build a namespace
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_NS_SEPARATOR',
                                             'atom'  => 'none')
        );
        
        $this->actions = array('atom'       => 'Identifier',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAMESPACE").each{ fullcode.setProperty('fullcode', "namespace " + it.getProperty('fullcode'));}

fullcode.has('atom', 'Identifier').each{ fullcode.setProperty('fullcode', "namespace"); }

fullcode.has('fullcode', null).filter{ it.out('NAMESPACE').count() == 0}.each{ fullcode.setProperty('fullcode', "namespace Global");}

GREMLIN;
    }
}

?>
