<?php

class a extends b {
    function c() {
        if ($d instanceof self) {}
        if ($e instanceof parent) {}
        if ($f instanceof static) {}
        if ($g instanceof Traversable) {}
        if ($g instanceof UndefinedInterface) {}
    }
}
?>