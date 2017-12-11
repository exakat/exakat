<?php

spl_autoload_register('Used::x');
spl_autoload_register('UsedButUndefined::x');
spl_autoload_register('UsedClass::x::multipledoublecolonIsAnError');
spl_autoload_register('Methodname');

print 'Unused2::x';

class Used {}
class Unused {}
class UnusedAndUnmentionned {}
