<?php
class Example {
    function foo() {
        return "foo!\n";
    }
}

// Rename the 'foo' method to 'bar'
runkit_method_rename(
    'Example',
    'foo',
    'bar'
);

runkit_variable_rename(
    'Example',
    'foo',
    'bar'
);

// output renamed function
echo Example::bar();
?>