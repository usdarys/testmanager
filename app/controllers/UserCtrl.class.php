<?php 

namespace app\controllers;

use core\App;
use core\Utils;

class UserCtrl {

    private $userList;

    public function __construct() {
        $this->userList = array();
    }

    public function action_userList() {
        $this->userList = App::getDB()->select("user_account", [
            "id",
            "login",
            "first_name",
            "last_name"   
        ]);

        $this->generateListView();
    }

    public function action_userCreate() {

    }

    public function action_userUpdate() {

    }

    public function action_userDelete() {
        
    }

    private function generateListView() {
        App::getSmarty()->assign('userList', $this->userList);
        App::getSmarty()->display('userList.tpl');
    }

}