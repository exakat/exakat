<?php

class HasNoChildren extends DSL {
    public function run(): Command {
        return new Stdclass;
    }

    public function run2(): \Command {
        return new Stdclass;
    }

    public function run3(): self {
        return new Stdclass;
    }

    public function run4(): parent {
        return new Stdclass;
    }

    public function run5(): string {
        return rand(1 ,2) ? new Stdclass : 'string';
    }

    public function run6(): Command {
        return rand(1 ,2) ? new Stdclass : 'string';
    }
}
?>
