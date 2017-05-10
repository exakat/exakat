<?php

interface I {
    const i = 2;
}
echo I::i;

class C {
    const i = 3;
}
echo C::i;

interface J {}


?>