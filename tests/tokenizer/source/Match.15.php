<?php

// opcache can't be certain Test::usesRef is actually this method
if (!class_exists('Test')) {
    class Test {
        public static function usesRef(&$x) {
            $x = 'modified';
        }
        public static function usesValue($x) {
            echo "usesValue $x\n";
        }
    }
}

function main() {
    $i = 0;
    Test::usesValue(match(true) { true => $i });
    echo "i is $i\n";
    $j = 1;
    Test::usesRef(match(true) { true => $j });
    echo "j is $j\n";
}
main();
