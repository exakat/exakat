<?php

spl_autoload_register('Used::x');
spl_autoload_register('UsedButUndefined::x');
spl_autoload_register('UsedClass::x::multipledoublecolonIsAnError');
spl_autoload_register('Methodname');

print 'Unused2::x';

class Used {
    function x() {}
}
class Unused {
    function x() {}
}

class Unused2 {
}

class UnusedAndUnmentionned {
    function x() {}
}
