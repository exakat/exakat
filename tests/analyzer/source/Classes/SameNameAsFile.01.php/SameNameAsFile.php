<?php

namespace A{
    class samenameasfile {}
}

namespace B {
    class SameNameAsFile {} // 
}

namespace C {
    trait SameNameAsFile {}
}

namespace D {
    interface SameNameAsFile {}
}

namespace E {
    interface NotSameNameAsFile {}
}

namespace F {
    trait samenameasfile {}
}

namespace G {
    interface samenameasfile {}
}
