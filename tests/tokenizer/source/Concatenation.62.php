<?php
    if(B('C', 'D') == 'E' && ($a == 'F' || ! $a )) {
        $a = new Stdclass();
        $a->I = 'I';

        print 'a2' . 'b2';
        print 'a3' . 'b3' . 'c3';
        print 'a4' . 'b4' . 'c4' . 'd4';
  
        print ('H' . $a->I . + (true ?'1':'0'));
    }
?>