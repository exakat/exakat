<?php

interface i {}

class a0             implements i {}
class a1 extends a0  implements i {}
class a2 extends a1  implements i {}
class a3 extends a2  implements i {}
class a4 extends a3  implements i {}


class b0             implements i {}
class b1 extends b0               {}
class b2 extends b1               {}
class b3 extends b2               {}
class b4 extends b3  implements i {}

class c0                          {}
class c1 extends c0               {}
class c2 extends c1               {}
class c3 extends c2               {}
class c4 extends c3  implements i {}