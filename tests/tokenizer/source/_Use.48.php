<?php

class Foo
{
    use TraitA, \TraitB, \A\TraitC, A\TraitD, namespace\TraitE;
    use TraitA1, \TraitB1, \A\TraitC1, A\TraitD1, namespace\TraitE1 {
        a::b as C;
    }
}
