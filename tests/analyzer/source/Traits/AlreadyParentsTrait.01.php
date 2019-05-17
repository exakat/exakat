<?php
 trait t{}
 
class a {
    use t, t1, t2;
}

class b extends a {
    use t, t3, t4;
}

class c extends b {
    use t5;
}

class d extends c {
}

class e extends d {
    use t3;
}

class f extends e {
    use tt1;
}