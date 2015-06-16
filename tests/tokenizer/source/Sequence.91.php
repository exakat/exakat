<?php

    function B ($a)
    {
        return new C(function () use ($a) {
            $b = $this->D();
            $b->E();
            for ($c = 0; $c < $a && $b->F(); ++$c)
                $b->G();
            while ($b->F()) {
                yield $b->I() => $b->J();
                $b->G();
            }
        });
    }
