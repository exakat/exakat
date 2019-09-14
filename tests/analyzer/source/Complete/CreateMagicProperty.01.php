<?php
    class MagicClass {
        public $p = 1;
        
        public function __set($n, $v) {}
        public function __get($a) {}
        public function __isset($a) {}
        public function __unset($a) {}
        
        public function foo(Magicclass $a) {
            
            echo $this->ar;
            echo $a->aur;
            echo $this->p;
            echo $a->p;
            $this->aw = 1;
            $a->auw = 2;
            $this->p = 1;
            $a->p = 1;

            isset($this->aui, $a->bui, $a->p);
                unset ($this->au);
                (unset) $a->bu;
                unset ($a->p);
                (unset) $a->p;
        }
    }
?>