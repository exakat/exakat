<?php

trait A {
    public function smallTalk() {
        echo 'a';
    }
    public function bigTalk() {
        echo 'A';
    }
}

trait B {
    public function smallTalk() {
        echo 'b';
    }
    public function bigTalk() {
        echo 'B';
    }
}

class Talker {
    use A, B {
        B::smallTalk2 insteadof A;
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
        A::bigTalk as protected;
        A::bigTalk3 as protected;
    }
}

new Talker();

?>