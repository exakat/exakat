<?php

class yPrivate extends xPrivate {
    private const yPrivate = 1;
}

// OK
class yProtected extends xPrivate {
    protected const yPrivate = 2;
}

// OK
class yPublic extends xPrivate {
    public const yPrivate = 3;
}

// OK
class yNone extends xPrivate {
    const yPrivate = 3;
}

////////////////////////////////////////////////////////////////
// OK
class yPrivate2 extends xProtected {
    private const yProtected = 4;
}

// OK
class yProtected2 extends xProtected {
    protected const yProtected = 5;
}

// OK
class yPublic2 extends xProtected {
    public const yProtected = 6;
}

// OK
class yNone2 extends xProtected {
    const yProtected = 3;
}


////////////////////////////////////////////////////////////////
class yPrivate3 extends xNone {
    private const yNone = 7;
}

class yProtected3 extends xNone {
    protected const yNone = 8;
}

// OK
class yPublic3 extends xNone {
    public const yNone = 9;
}

// OK
class yNone3 extends xNone {
    const yNone = 3;
}


////////////////////////////////////////////////////////////////
class yPrivate4 extends xPublic {
    private const yPublic = 7;
}

class yProtected4 extends xPublic {
    protected const yPublic = 8;
}

// OK
class yPublic4 extends xPublic {
    public const yPublic = 9;
}

// OK
class yNone4 extends xPublic {
    const xPublic = 3;
}


?>