<?php

namespace app\controllers;

use app\forms\LoginForm;
use core\App;
use core\ParamUtils;
use core\RoleUtils;
use core\Utils;

class LoginCtrl {

    private $form;

    public function __construct() {
        $this->form = new LoginForm();
    }

    public function action_login() {
        if ($this->validateForm()) {
            $user = App::getDB()->select("user_account", "id", [
                "AND" => [
                    "login" => $this->form->login,
                    "password" => $this->form->password
                ]
            ]);

            if (!empty($user)) {
                $roles = App::getDB()->select("role", [
                        "[><]user_account_role" => ["id" => "role_id"]
                    ], [
                        "role.name"
                    ], [
                        "user_account_id" => $user[0]
                    ]
                );

                foreach ($roles as $role) {
                    RoleUtils::addRole($role["name"]);
                }
                App::getRouter()->redirectTo("hello");
            } else {
                Utils::addErrorMessage("Nieprawidłowe dane logowania");
                $this->generateView();
            }
        } else {
            $this->generateView();
        }   
    }

    private function validateForm() {
        $this->form->login = ParamUtils::getFromPost("login");
        $this->form->password = ParamUtils::getFromPost("password");

        if (!isset($this->form->login) || !isset($this->form->password)) {
            return false;
        }

        if (empty($this->form->login)) {
            Utils::addErrorMessage('Podaj login');
        }

        if (empty($this->form->password)) {
            Utils::addErrorMessage('Podaj hasło');
        }

        if (App::getMessages()->isError()) {
            return false;
        }

        return true;
    }

    public function action_logout() {
        session_destroy();
        Utils::addInfoMessage('Zostałeś wylogowany');
        $this->generateView();
    }

    private function generateView() {
        App::getSmarty()->assign("form", $this->form);
        App::getSmarty()->display("login.tpl");
    }
}