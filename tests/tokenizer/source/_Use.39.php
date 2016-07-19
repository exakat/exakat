<?php

class a
{
    use b {
        c::reset as d;
    }

    /** @var string */
    protected $e;
}
