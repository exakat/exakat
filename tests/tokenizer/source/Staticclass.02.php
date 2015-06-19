<?php
namespace NS1 {
    class ClassName1 {}
}
namespace NS {
    use NS1\ClassName1 as X;
    use NS1\ClassName1;

    class ParentClass {}
    class ClassName extends ParentClass {
    
        static function x() {
            echo self::class;
            echo static::class;
            echo parent::class;
        }
    }
    echo X::class."\n";
    echo ClassName1::class."\n";
    echo NS1\ClassName1::class."\n";
    echo \NS1\ClassName1::class."\n";
}


?>