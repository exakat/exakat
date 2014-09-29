<?php
namespace NS {
    class ParentClass {}
    class ClassName extends ParentClass {
    
        static function x() {
            echo self::class;
            echo static::class;
            echo parent::class;
        }
    }
    echo ClassName::class;
    $x = 'ClassName';
    echo \NS\ClassName::class;
    echo \X\B\ClassName::class;
    echo ClassName::x();
    
}

?>