<?php 

new class () {
    function parser() {
        tail_call: {
            $a++;
        }
        
        goto tail_call;
    }
};
