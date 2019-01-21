<?php

class x {
    protected function z_pro() {}
    private function z_pri() {}
    public function z_pub() {}
}

interface i {
    function y_pro();
    function y_pri();
    function y_pub();
}

class xx implements i {
    protected function y_pro() {}
    private function y_pri() {}
    public function y_pub() {}
    public function w_pub() {}
    private function w_pri() {}
    protected function w_pro() {}
}

?>
