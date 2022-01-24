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

    private function validateForm() {
        $this->form->id = ParamUtils::getFromPost('id');
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

        try {
            if (empty($this->form->id)) {
                $isLoginUsed = App::getDB()->has("user_account", [
                    "login" => $this->form->login
                ]);
            } else {
                $isLoginUsed = App::getDB()->has("user_account", [
                    "AND" => [
                        "login" => $this->form->login,
                        "id[!]" => $this->form->id
                    ]
                ]);
            }
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd walidacji loginu " . $e->getMessage());
        }

        if ($isLoginUsed) {
            Utils::addErrorMessage('Podany login jest już zajęty');
        }

        $this->form->firstName = $v->validate($this->form->firstName, [
            'required' => true,
            'required_message' => "Imie jest wymagane",
            'regexp' => '/^[A-Za-z\cx]+$/',
            'max_length' => 30,
            'validator_message' => "Nieprawidłowe imie"
        ]);

        $this->form->lastName = $v->validate($this->form->lastName, [
            'required' => true,
            'required_message' => "Nazwisko jest wymagane",
            'regexp' => '/^[A-Za-z\cx]+$/',
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

    public function action_userSave() {
        if ($this->validateForm()) {
            if (empty($this->form->id)) {
                App::getDB()->insert("user_account", [
                    "login" => $this->form->login,
                    "first_name" => $this->form->firstName,
                    "last_name" => $this->form->lastName,
                    "password" => $this->form->password
                ]);

                $this->form->id = App::getDB()->id();
                $this->saveRoles();
                Utils::addInfoMessage("Dodano użytkownika");
            } else {            
                App::getDB()->update("user_account", [
                    "login" => $this->form->login,
                    "first_name" => $this->form->firstName,
                    "last_name" => $this->form->lastName,
                    "password" => $this->form->password
                ], [
                    "id" => $this->form->id
                ]);

                $this->saveRoles();
                Utils::addInfoMessage("Zapisano użytkownika");
            }
            App::getRouter()->forwardTo("userList");
        }

        $this->generateFormView();
    }

    private function saveRoles() {
        if (!empty($this->form->roles)) {
            foreach ($this->form->roles as $r => $val) {
                App::getDB()->query('
                    INSERT INTO user_account_role (user_account_id, role_id) 
                    VALUES (' . $this->form->id . ', (SELECT id FROM role WHERE name = \'' . $val . '\'));
                ');
            }
        }        
    }

    public function action_userUpdate() {
        $id = $this->getResourceId();

        if ($id) {
            try {
                $user = App::getDB()->get("user_account", "*", [
                    "id" => $id
                ]);
                $this->form->id = $user["id"];
                $this->form->login = $user["login"];
                $this->form->firstName = $user["first_name"];
                $this->form->lastName = $user["last_name"];
                $this->form->password = $user["password"];
                $this->form->roles = App::getDB()->select("role", [
                        "[><]user_account_role" => ["id" => "role_id"]
                    ], [
                        "role.name"
                    ], [
                        "user_account_id" => $this->form->$id
                    ]
                );

            } catch (\PDOException $e) {
                Utils::addErrorMessage("Błąd podczas pobierania danych uzytkownika do edycji " . $e->getMessage());
            }

            $this->generateFormView();
        }
    }

    public function action_userDelete() { 
        $id = $this->getResourceId();

        if ($id) {
            try {
                App::getDB()->pdo->beginTransaction();

                App::getDB()->delete("user_account_role", [
                    "user_account_id" => $id
                ]);
    
                App::getDB()->delete("user_account", [
                    "id" => $id
                ]);
    
                App::getDB()->pdo->commit();
            } catch (\PDOException $e){
                App::getDB()->pdo->rollBack();
                Utils::addErrorMessage("Nie udało się usunąć użytkownika. " . $e->getMessage());
            }

            if (!App::getMessages()->isError()) {
                Utils::addInfoMessage("Usunięto użytkownika");
            } 
        }

        App::getRouter()->forwardTo("userList");
    }

    private function getResourceId() {
        $v = new Validator();
        $id = $v->validateFromCleanURL(1, [
            "required" => true,
            "required_message" => "Brak id",
            "int" => true,
            "min" => 1,
            "validator_message" => "Nieprawidlowe id"
        ]);
        if ($v->isLastOK()) {
            return $id;
        }
        return;
    }

    private function generateFormView() {
        try {
            $roles = App::getDB()->select("role", "name");
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania ról " . $e->getMessage());
        }

        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->assign('roles', $roles);
        App::getSmarty()->display('userForm.tpl');
    }

    public function action_userList() {
        try {
            $userList = App::getDB()->select("user_account", [
                "id",
                "login",
                "first_name",
                "last_name"   
            ]);
        } catch (\PDOException $e) {
            Utils::addErrorMessage("Błąd pobierania listy uzytkownikow " . $e->getMessage());
        }

        App::getSmarty()->assign('userList', $userList);
        App::getSmarty()->display('userList.tpl');
    }

}