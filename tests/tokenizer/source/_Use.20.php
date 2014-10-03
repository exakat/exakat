<?php
namespace B\C;

use D\E;


class F extends G
{
    use \H\I\J\K {
        L as public;
        M as private;
        N as protected;
    }
}
    
