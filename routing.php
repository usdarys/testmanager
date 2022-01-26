<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('testRunList'); #default action
App::getRouter()->setLoginRoute('login'); #action to forward if no permissions

Utils::addRoute('login', 'LoginCtrl');
Utils::addRoute('logout', 'LoginCtrl');
Utils::addRoute('userList', 'UserCtrl', ['Admin']);
Utils::addRoute('userSave', 'UserCtrl', ['Admin']);
Utils::addRoute('userUpdate', 'UserCtrl', ['Admin']);
Utils::addRoute('userDelete', 'UserCtrl', ['Admin']);
Utils::addRoute('testCaseList', 'TestCaseCtrl', ['Admin']);
Utils::addRoute('testCaseSave', 'TestCaseCtrl', ['Admin']);
Utils::addRoute('testRunList', 'TestRunCtrl', ['Admin']);
Utils::addRoute('testRunSave', 'TestRunCtrl', ['Admin']);
Utils::addRoute('testResultList', 'TestResultCtrl', ['Admin']);