<?php

class a {
    use b,
        c,
        d {
        e::foo insteadof f, g, h;
        e::foo2 insteadof f, g, h;
    }
}
