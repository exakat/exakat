<?php
class B extends \C\D\E
{
    use F, G {
        G::I insteadof F;
        F::L insteadof G;
    }

}
