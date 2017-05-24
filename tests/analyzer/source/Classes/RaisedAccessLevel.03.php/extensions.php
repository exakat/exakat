<?php

class yPrivate extends xProtected {
    private function foo($yPrivate) {}
}

// OK
class yProtected extends xProtected {
    private function foo($yProtected) {}
}

// OK
class yPublic extends xProtected {
    private function foo($yPublic) {}
}

class yPrivate2 extends xPublic {
    private function foo($yPrivate2) {}
}

class yProtected2 extends xPublic {
    protected function foo($yProtected2) {}
}

// OK
class yPublic2 extends xPublic {
    protected function foo($yPublic2) {}
}

class yPrivate3 extends xNone {
    private function foo($yPrivate3) {}
}

class yProtected3 extends xNone {
    protected function foo($yProtected3) {}
}

class yPublic3 extends xNone {
    protected function foo($yPublic3) {}
}


?>