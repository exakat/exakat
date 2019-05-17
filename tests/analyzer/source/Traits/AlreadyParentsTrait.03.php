<?php

trait t2 {
    use ta;
}

trait t1 {
    use t2;
}

class x {
    use t1, ta;
}
?>