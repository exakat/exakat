<?php

namespace Tokenizer;

class _Try extends TokenAuto {
    static public $operators = array('T_TRY');
    static public $atom = 'Try';

    public function _check() {
        // Try () { } catch
        $this->conditions = array(0 => array('token'    => _Try::$operators,
                                             'atom'     => 'none'),
                                  1 => array('atom'     => 'Sequence',
                                             'property' => array('block' => 'true')), 
                                  2 => array('atom'     => array('Catch', 'Finally')),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'CODE',
                                                        2 => 'CATCH'),
                               'order'        => array( 2 => 0),
                               'atom'         => 'Try',
                               'keepIndexed'  => true);
        $this->checkAuto();

        // Try () { } catch + new catch
        $this->conditions = array(0 => array('atom'  => 'yes', 
                                             'token' => _Try::$operators),
                                  1 => array('atom'  => array('Catch', 'Finally'))
                                  );
        $this->actions = array('to_catch'    => array( 1 => 'CATCH' ),
                               'keepIndexed' => true,
                               'order'       => array(1 => 0));
        $this->checkAuto();

        // Try () NO catch 
        $this->conditions = array(0 => array('atom'     => 'yes', 
                                             'token'    => _Try::$operators),
                                  1 => array('notToken' => array('T_CATCH', 'T_FINALLY'))
                                  );
        $this->actions = array('cleanIndex'  => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("CATCH").each{ s.add(it.fullcode); }
fullcode.setProperty('fullcode', "try " + it.out("CODE").next().getProperty('fullcode') + s.join(" ")); 
fullcode.setProperty('count', it.out("CATCH").count()); 

GREMLIN;
    }
}

?>