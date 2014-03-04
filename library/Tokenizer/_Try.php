<?php

namespace Tokenizer;

class _Try extends TokenAuto {
    static public $operators = array('T_TRY');

    public function _check() {
        // Try () { } catch
        $this->conditions = array(0 => array('token' => _Try::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Block'), 
                                  2 => array('atom' => 'Catch'),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'CODE',
                                                      2 => 'CATCH'),
                               'order'      => array( 2 => 0),
                               'atom'       => 'Try',
                               'keepIndexed' => true);
        $this->checkAuto();

        // Try () { } catch + new catch
        $this->conditions = array(0 => array('atom'  => 'yes', 
                                             'token' => _Try::$operators),
                                  1 => array('atom'  => 'Catch')
                                  );
        $this->actions = array('to_catch'    => array( 1 => 'CATCH' ),
                               'keepIndexed' => true,
                               'order'       => array(1 => 0));
        $this->checkAuto();

        // Try () NO catch 
        $this->conditions = array(0 => array('atom'  => 'yes', 
                                             'token' => _Try::$operators),
                                  1 => array('notToken'  => 'T_CATCH')
                                  );
        $this->actions = array('cleanIndex'  => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return '
s = [];
it.out("CATCH").each{ s.add(it.fullcode); }        
it.fullcode = "try " + it.out("CODE").next().fullcode + s.join(" "); ';
    }
}

?>