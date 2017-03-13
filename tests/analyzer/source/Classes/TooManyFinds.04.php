<?php

// quite a fishy interface
trait tUserKO {
    public function findByEmail($email) {}
    public function findByUsername($username) {}
    public function findByFirstName($firstname){}
    public function findByLastName($lastname){}
    public function findByName($name){}
    public function findById($id){}

    public function insert($user){}
    public function update($user){}
}

trait tUserOK {
    public function findByEmail($email) {}
    public function findByUsername($username) {}

    public function insert($user){}
    public function update($user){}
}
?>