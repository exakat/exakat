<?php

spl_autoload_register('Used::x');
spl_autoload_register('Used2::x');
spl_autoload_register('UsedButUndefined::x');

print 'Unused2::x';

class Used {
    function x() {}
}
class Used2 {
    function x() {}
}
class Unused {
    function x() {}
}
