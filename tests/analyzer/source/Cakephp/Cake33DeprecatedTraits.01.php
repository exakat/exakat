<?php
class x {
    use Cake\Routing\RequestActionTrait;
    
}

class x2 {
    use Cake\Routing\RequestActionTrait, B {
        RequestActionTrait::smallTalk insteadof A;
        A::bigTalk insteadof RequestActionTrait;
    }
    
}

class x4 {
    use Cake\Routing\RequestActionTrait, C;
    
}

class x8 {
    use Cake\Routing\RequestActionTrait3, A;
    
}

?>