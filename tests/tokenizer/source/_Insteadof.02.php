<?php

class a {
    use b,
        c,
        d {
        e::foo insteadof f, g;
    }
}
