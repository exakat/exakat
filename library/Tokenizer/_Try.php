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

class _Try extends TokenAuto {
    static public $operators = array('T_TRY');
    static public $atom = 'Try';

    public function _check() {
        // Try () { } catch
        $this->conditions = array(0 => array('token'    => _Try::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Try')),
                                  2 => array('atom'     => array('Sequence', 'Void')),
                                  3 => array('token'    => 'T_CLOSE_CURLY'),
                                  4 => array('atom'     => array('Catch', 'Finally')),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CODE',
                                                        3 => 'DROP',
                                                        4 => 'CATCH'),
                               'rank'         => array( 4 => 0),
                               'atom'         => 'Try',
                               'keepIndexed'  => true);
        $this->checkAuto();

        // Try () { } catch + new catch
        $this->conditions = array(0 => array('atom'  => 'yes',
                                             'token' => _Try::$operators),
                                  1 => array('atom'  => array('Catch', 'Finally'))
                                  );
        $this->actions = array('toCatch'     => array( 1 => 'CATCH' ),
                               'keepIndexed' => true,
                               'rank'        => array(1 => 0));
        $this->checkAuto();

        // Try () NO catch
        $this->conditions = array(0 => array('atom'     => 'yes',
                                             'token'    => _Try::$operators),
                                  1 => array('notToken' => array('T_CATCH', 'T_FINALLY'))
                                  );
        $this->actions = array('cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("CATCH").sort{it.rank}.each{ s.add(it.fullcode); }
fullcode.setProperty('fullcode', "try " + it.out("CODE").next().getProperty('fullcode') + s.join(" "));
fullcode.setProperty('count', it.out("CATCH").count());

GREMLIN;
    }
}

?>
