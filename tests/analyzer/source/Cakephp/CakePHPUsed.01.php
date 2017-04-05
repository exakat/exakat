<?php

use \cake\view\celltrait as Celltrait;

// \File is a CakePHP class
new File($poFile, true);

class foo implements CakeEventListener {
    use Celltrait;
}

// Not Cake classes
new NotCakeFile($poFile, true);

class foo implements NotCakeCakeEventListener {
    use NotCakeFileConfigTrait;
}

?>