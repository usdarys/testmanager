<?php

namespace app\controllers;

use core\Utils;
use core\App;

class TestCtrl {
    public function action_test() {
        Utils::addInfoMessage("Test message");   
        App::getSmarty()->display("Hello.tpl");
    }
}