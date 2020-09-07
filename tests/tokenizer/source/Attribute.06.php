<?php
// https://wiki.php.net/rfc/attributes_v2#attribute_syntax
namespace My\Attributes {
    use Attribute;

    #[Attribute]
    class SingleArgument {
        public $argumentValue;

        public function __construct($argumentValue) {
             $this->argumentValue = $argumentValue;
        }
    }
}

namespace {
    use My\Attributes\SingleArgument;

    #[SingleArgument("Hello World")]
    class Foo {
    }

    $reflectionClass = new \ReflectionClass(Foo::class);
    $attributes = $reflectionClass->getAttributes();

    var_dump($attributes[0]->getName());
    var_dump($attributes[0]->getArguments());
    var_dump($attributes[0]->newInstance());
}
?>