<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('hello'); #default action
App::getRouter()->setLoginRoute('login'); #action to forward if no permissions

Utils::addRoute('login', 'LoginCtrl');
Utils::addRoute('logout', 'LoginCtrl');
Utils::addRoute('userList', 'UserCtrl', ['Admin']);
Utils::addRoute('userSave', 'UserCtrl', ['Admin']);
Utils::addRoute('userUpdate', 'UserCtrl', ['Admin']);
Utils::addRoute('userDelete', 'UserCtrl', ['Admin']);
Utils::addRoute('hello', 'HelloCtrl', ['Admin', 'Tester']);