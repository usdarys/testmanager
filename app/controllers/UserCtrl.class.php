<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\ParamUtils;
use app\forms\UserForm;
use core\Validator;

class UserCtrl {

    private $form;

    public function __construct() {
        $this->form = new UserForm();
    }

    public function action_userList() {
        $userList = App::getDB()->select("user_account", [
            "id",
            "login",
            "first_name",
            "last_name"   
        ]);

        App::getSmarty()->assign('userList', $userList);
        App::getSmarty()->display('userList.tpl');
    }

    public function action_userCreate() {
        if ($this->validateForm()) {
            App::getDB()->insert("user_account", [
                "login" => $this->form->login,
                "first_name" => $this->form->firstName,
                "last_name" => $this->form->lastName,
                "password" => $this->form->password
            ]);

            $userId = App::getDB()->id();

            if (!empty($this->form->roles)) {
                foreach ($this->form->roles as $r => $val) {
                    App::getDB()->query('
                        INSERT INTO user_account_role (user_account_id, role_id) 
                        VALUES (' . $userId . ', (SELECT id FROM role WHERE name = \'' . $val . '\'));
                    ');
                }
            }

            Utils::addInfoMessage("Dodano użytkownika");
            App::getRouter()->forwardTo("userList");
        }

        $roles = App::getDB()->select("role", "name");

        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->assign('roles', $roles);
        App::getSmarty()->display('userForm.tpl');
    }

    private function validateForm() {
        $this->form->login = ParamUtils::getFromPost('login');
        $this->form->firstName = ParamUtils::getFromPost('first_name');
        $this->form->lastName = ParamUtils::getFromPost('last_name');
        $this->form->password = ParamUtils::getFromPost('password');
        $this->form->roles = Utils::preg_grep_keys('/^role_/', $_POST);

        if (!isset($this->form->login) || !isset($this->form->password) || !isset($this->form->firstName) || !isset($this->form->lastName)) {
            return false;
        }

        $v = new Validator();

        $this->form->login = $v->validate($this->form->login, [
            'required' => true,
            'required_message' => "Login jest wymagany",
            'regexp' => '/^[A-Za-z][A-Za-z0-9]+$/',
            'min_length' => 3,
            'max_length' => 20,
            'validator_message' => "Nieprawidłowy login. Login powinien zaczynac się od litery i składać z 3 - 20 liter lub cyfr"
        ]);

        $isLoginUsed = App::getDB()->count("user_account", ["login" => $this->form->login]);
        if ($isLoginUsed) {
            Utils::addErrorMessage('Podany login jest już zajęty');
        }

        $this->form->firstName = $v->validate($this->form->firstName, [
            'required' => true,
            'required_message' => "Imie jest wymagane",
            'regexp' => '/^[A-Za-z]+$/',
            'max_length' => 30,
            'validator_message' => "Nieprawidłowe imie"
        ]);

        $this->form->lastName = $v->validate($this->form->lastName, [
            'required' => true,
            'required_message' => "Nazwisko jest wymagane",
            'regexp' => '/^[A-Za-z]+$/',
            'max_length' => 30,
            'validator_message' => "Nieprawidłowe nazwisko"
        ]);

        $this->form->password = $v->validate($this->form->password, [
            'required' => true,
            'required_message' => "Hasło jest wymagane",
            'regexp' => '/^[A-Za-z0-9]+$/',
            'min_length' => 3,
            'max_length' => 20,
            'validator_message' => "Nieprawidłowe hasło. Hasło powinno się składać z 3 - 20 liter i cyfr"
        ]);

        if (App::getMessages()->isError()) {
            return false;
        }

        return true;
    }

    public function action_userUpdate() {
        // TODO
    }

    public function action_userDelete() {
        // TODO
    }

}