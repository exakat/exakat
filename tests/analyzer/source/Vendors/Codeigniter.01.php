<?php
// A code igniter controller
class Blog extends CI_Controller {

        public function index()
        {
                echo 'Hello World!';
        }
}

// Not a code igniter controller
class Blog2 extends Controller {

        public function index()
        {
                echo 'Hello World!';
        }
}


?>