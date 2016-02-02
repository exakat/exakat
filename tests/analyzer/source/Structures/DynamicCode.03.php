<?php

class fooParent {}

class foo extends fooParent {
    function a() {
        static::b();
        self::c();
        parent::d();
        $parent::d();
    }
}

echo __('something');
print __('something to print');

die ( __('something to die for'));
exit ( __('something to exit'));


$v['a' . 'b'];
v('a' . 'b');

