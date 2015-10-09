<?php

class a implements b { }
class a2 implements \b2 { }
class a3 implements b3,c3 { }
class a23 implements \b3, \c3 { }
class a24 implements \b3, c3\d33 { }

class a4 implements b4,c4,d4 { }
class a5 implements b4,c4,d4,e5 { }
class a6 implements b4,\cc\c4,\d4, e5\f5 { }

