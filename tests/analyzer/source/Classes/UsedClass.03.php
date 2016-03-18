<?php

spl_autoload_register('Used::x');
spl_autoload_register('UsedButUndefined::x');

print 'Unused::x';

class Used {}
class Unused {}
